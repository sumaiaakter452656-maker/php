<?php
require __DIR__ . '/session_bootstrap.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$loginError = '';
$loginNotice = isset($_GET['registered']) ? 'Account created. You can now log in.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/dbconfig.php';

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $loginError = 'Enter your email and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && hash_equals($user['password_hash'], md5($password))) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header('Location: dashboard.php');
            exit;
        }

        $loginError = 'Invalid email or password.';
    }
}

require __DIR__ . '/auth-cover-login.php';

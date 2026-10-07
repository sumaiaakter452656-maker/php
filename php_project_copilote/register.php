<?php
require __DIR__ . '/session_bootstrap.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$registrationError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/dbconfig.php';

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) > 100 || strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $registrationError = 'Enter your name, a valid email, and a password.';
    } elseif (strlen($password) < 8) {
        $registrationError = 'Your password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $registrationError = 'The passwords do not match.';
    } else {
        $passwordHash = md5($password);
        $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $passwordHash);

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: index.php?registered=1');
            exit;
        }

        if ($stmt->errno === 1062) {
            $registrationError = 'An account with that email already exists.';
        } else {
            $error = $stmt->error;
            $stmt->close();
            die('Could not create account: ' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8'));
        }

        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create account</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/icons.css">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/dark-theme.css" rel="stylesheet">
</head>
<body>
  <main class="section-authentication-cover">
    <div class="row g-0 min-vh-100">
      <div class="col-12 col-md-8 col-lg-5 m-auto">
        <div class="card border-0 m-3">
          <div class="card-body p-sm-5">
            <h4 class="fw-bold">Create your account</h4>
            <p class="mb-4">Register to access the dashboard.</p>
            <?php if ($registrationError !== ''): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($registrationError, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form class="row g-3" method="post" action="register.php">
              <div class="col-12">
                <label for="name" class="form-label">Name</label>
                <input class="form-control" id="name" name="name" maxlength="100" autocomplete="name" required>
              </div>
              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="254" autocomplete="email" required>
              </div>
              <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" autocomplete="new-password" required>
              </div>
              <div class="col-12">
                <label for="confirm_password" class="form-label">Confirm password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" autocomplete="new-password" required>
              </div>
              <div class="col-12 d-grid">
                <button type="submit" class="btn btn-primary">Create account</button>
              </div>
              <div class="col-12">
                <p class="mb-0">Already have an account? <a href="index.php">Log in</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

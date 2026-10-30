<?php
require_once 'includes/functions.php';

if (is_logged_in()) {
    redirect(dashboard_path(current_role()));
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // ইমেইল ভুল নাকি পাসওয়ার্ড ভুল, আলাদা করে বলা হয় না (নিরাপত্তার জন্য)
    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।';
    } elseif ($user['status'] === 'blocked') {
        $error = 'আপনার অ্যাকাউন্ট বন্ধ করা হয়েছে। অ্যাডমিনের সাথে যোগাযোগ করুন।';
    } else {
        // session fixation আটকাতে নতুন session id
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        // পরে বারবার query না করতে profile id session এ রাখা হচ্ছে
        if ($user['role'] === 'employer') {
            $stmt = $pdo->prepare('SELECT id FROM companies WHERE user_id = ?');
            $stmt->execute([$user['id']]);
            $_SESSION['company_id'] = (int) $stmt->fetchColumn();
        } elseif ($user['role'] === 'seeker') {
            $stmt = $pdo->prepare('SELECT id FROM seeker_profiles WHERE user_id = ?');
            $stmt->execute([$user['id']]);
            $_SESSION['seeker_id'] = (int) $stmt->fetchColumn();
        }

        set_flash('success', 'স্বাগতম, ' . $user['name'] . '!');
        redirect(dashboard_path($user['role']));
    }
}

$page_title = 'Login';
require 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">লগইন</h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="email" value="<?= e($email) ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                    <p class="text-center mt-3 mb-0">
                        অ্যাকাউন্ট নেই? <a href="register.php">রেজিস্টার করুন</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>

<?php
require_once 'includes/functions.php';

if (is_logged_in()) {
    redirect(dashboard_path(current_role()));
}

$errors = [];
$old = ['name' => '', 'email' => '', 'phone' => '', 'role' => 'seeker', 'company_name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $old['name']         = trim($_POST['name'] ?? '');
    $old['email']        = trim($_POST['email'] ?? '');
    $old['phone']        = trim($_POST['phone'] ?? '');
    $old['role']         = $_POST['role'] ?? 'seeker';
    $old['company_name'] = trim($_POST['company_name'] ?? '');
    $password            = $_POST['password'] ?? '';
    $confirm             = $_POST['confirm_password'] ?? '';

    // ---------- Validation ----------
    if ($old['name'] === '') {
        $errors['name'] = 'নাম দিন।';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'সঠিক ইমেইল দিন।';
    }
    // admin role ফর্ম থেকে নেওয়া যাবে না
    if (!in_array($old['role'], ['employer', 'seeker'], true)) {
        $errors['role'] = 'সঠিক অ্যাকাউন্ট টাইপ বেছে নিন।';
    }
    if ($old['role'] === 'employer' && $old['company_name'] === '') {
        $errors['company_name'] = 'কোম্পানির নাম দিন।';
    }
    if (strlen($password) < 6) {
        $errors['password'] = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
    }
    if ($password !== $confirm) {
        $errors['confirm_password'] = 'দুটি পাসওয়ার্ড মিলছে না।';
    }

    // ইমেইল আগে থেকে আছে কিনা
    if (empty($errors['email'])) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$old['email']]);
        if ($stmt->fetch()) {
            $errors['email'] = 'এই ইমেইল দিয়ে আগেই অ্যাকাউন্ট খোলা হয়েছে।';
        }
    }

    // ---------- Save ----------
    if (!$errors) {
        try {
            // users আর profile দুটো table এ একসাথে insert, তাই transaction
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $old['name'],
                $old['email'],
                password_hash($password, PASSWORD_DEFAULT),
                $old['role'],
                $old['phone'] ?: null,
            ]);
            $user_id = (int) $pdo->lastInsertId();

            if ($old['role'] === 'employer') {
                $stmt = $pdo->prepare('INSERT INTO companies (user_id, company_name) VALUES (?, ?)');
                $stmt->execute([$user_id, $old['company_name']]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO seeker_profiles (user_id) VALUES (?)');
                $stmt->execute([$user_id]);
            }

            $pdo->commit();

            set_flash('success', 'রেজিস্ট্রেশন সফল হয়েছে। এখন লগইন করুন।');
            redirect('login.php');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['general'] = 'কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।';
        }
    }
}

$page_title = 'Register';
require 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">নতুন অ্যাকাউন্ট</h3>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger"><?= e($errors['general']) ?></div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label d-block">অ্যাকাউন্ট টাইপ</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_seeker"
                                   value="seeker" <?= $old['role'] === 'seeker' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="role_seeker">চাকরিপ্রার্থী</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role" id="role_employer"
                                   value="employer" <?= $old['role'] === 'employer' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="role_employer">নিয়োগকর্তা</label>
                        </div>
                        <?php if (!empty($errors['role'])): ?>
                            <div class="text-danger small"><?= e($errors['role']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3" id="company_box">
                        <label class="form-label">কোম্পানির নাম</label>
                        <input type="text" name="company_name" value="<?= e($old['company_name']) ?>"
                               class="form-control <?= isset($errors['company_name']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e($errors['company_name'] ?? '') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">নাম</label>
                        <input type="text" name="name" value="<?= e($old['name']) ?>"
                               class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e($errors['name'] ?? '') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="email" value="<?= e($old['email']) ?>"
                               class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e($errors['email'] ?? '') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ফোন (ঐচ্ছিক)</label>
                        <input type="text" name="phone" value="<?= e($old['phone']) ?>" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড</label>
                        <input type="password" name="password"
                               class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e($errors['password'] ?? '') ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">পাসওয়ার্ড আবার দিন</label>
                        <input type="password" name="confirm_password"
                               class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e($errors['confirm_password'] ?? '') ?></div>
                    </div>

                    <button class="btn btn-primary w-100">Register</button>
                    <p class="text-center mt-3 mb-0">
                        অ্যাকাউন্ট আছে? <a href="login.php">লগইন করুন</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Employer বেছে নিলেই কোম্পানির নামের ঘর দেখাবে
function toggleCompany() {
    const isEmployer = document.getElementById('role_employer').checked;
    document.getElementById('company_box').style.display = isEmployer ? 'block' : 'none';
}
document.querySelectorAll('input[name="role"]').forEach(r => r.addEventListener('change', toggleCompany));
toggleCompany();
</script>

<?php require 'includes/footer.php'; ?>

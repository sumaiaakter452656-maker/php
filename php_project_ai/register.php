<?php
require_once __DIR__ . '/auth.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$registrationError = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    $emailInput = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (
        !is_string($postedToken)
        || !isset($_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $postedToken)
    ) {
        $registrationError = 'Your session has expired. Please try again.';
    } elseif (!is_string($emailInput) || !is_string($password) || !is_string($confirmPassword)) {
        $registrationError = 'Enter a valid email address and password.';
    } else {
        $email = strtolower(trim($emailInput));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registrationError = 'Enter a valid email address.';
        } elseif (strlen($password) < 8 || strlen($password) > 72) {
            $registrationError = 'Choose a password between 8 and 72 characters.';
        } elseif (!hash_equals($password, $confirmPassword)) {
            $registrationError = 'The passwords do not match.';
        } else {
            require_once __DIR__ . '/dbconfig.php';
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $statement = mysqli_prepare(
                $conn,
                "INSERT INTO users (email, password_hash, role) VALUES (?, ?, 'customer')"
            );

            if ($statement === false) {
                error_log('Registration query preparation failed: ' . mysqli_error($conn));
                $registrationError = 'Unable to create your account right now. Please try again later.';
            } else {
                mysqli_stmt_bind_param($statement, 'ss', $email, $passwordHash);
                if (!mysqli_stmt_execute($statement)) {
                    if (mysqli_stmt_errno($statement) === 1062) {
                        $registrationError = 'An account with that email already exists.';
                    } else {
                        error_log('Registration query failed: ' . mysqli_stmt_error($statement));
                        $registrationError = 'Unable to create your account right now. Please try again later.';
                    }
                } else {
                    $userId = mysqli_insert_id($conn);
                    mysqli_stmt_close($statement);
                    mysqli_close($conn);

                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = 'customer';
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    header('Location: dashboard.php');
                    exit;
                }

                mysqli_stmt_close($statement);
            }

            mysqli_close($conn);
        }
    }
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create account | Roksyn</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/icons.css">
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/dark-theme.css" rel="stylesheet">
</head>
<body>
  <main class="section-authentication-cover">
    <div class="auth-cover-right min-vh-100 d-flex align-items-center justify-content-center">
      <div class="card border-0 m-3" style="width: min(100%, 480px);">
        <div class="card-body p-4 p-sm-5">
          <img src="assets/images/logo-icon.png" class="mb-4" width="45" alt="Roksyn">
          <h1 class="h4 fw-bold">Create your customer account</h1>
          <p class="text-body-secondary mb-4">Sign up to get started. Staff and admin accounts are provisioned separately.</p>
          <form class="row g-3" method="post" action="register.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <?php if ($registrationError !== ''): ?>
              <div class="col-12">
                <div class="alert alert-danger mb-0" role="alert"><?= htmlspecialchars($registrationError, ENT_QUOTES, 'UTF-8') ?></div>
              </div>
            <?php endif; ?>
            <div class="col-12">
              <label for="inputEmailAddress" class="form-label">Email</label>
              <input type="email" class="form-control" id="inputEmailAddress" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required>
            </div>
            <div class="col-12">
              <label for="inputPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="inputPassword" name="password" minlength="8" maxlength="72" autocomplete="new-password" required>
            </div>
            <div class="col-12">
              <label for="inputConfirmPassword" class="form-label">Confirm password</label>
              <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" minlength="8" maxlength="72" autocomplete="new-password" required>
            </div>
            <div class="col-12 d-grid">
              <button type="submit" class="btn btn-primary">Create account</button>
            </div>
            <div class="col-12">
              <p class="mb-0">Already have an account? <a href="index.php">Sign in</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

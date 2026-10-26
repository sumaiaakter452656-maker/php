<?php
$dashboardLabels = [
    'admin' => 'Administrator',
    'customer' => 'Customer',
    'staff' => 'Staff',
];
$dashboardRoleLabel = $dashboardLabels[$dashboardRole];
$userEmail = $_SESSION['user_email'] ?? 'User';
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
  <head>
    <?php require __DIR__ . '/head.php'; ?>
  </head>
  <body>
    <?php require __DIR__ . '/header.php'; ?>
    <?php require __DIR__ . '/sidebar.php'; ?>
    <main class="page-content">
      <div class="container-fluid px-0">
        <section id="overview" class="mb-4">
          <p class="text-uppercase text-primary fw-semibold small mb-2"><?= htmlspecialchars($dashboardRoleLabel, ENT_QUOTES, 'UTF-8') ?> workspace</p>
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
              <h1 class="h3 fw-bold mb-1"><?= htmlspecialchars($dashboardTitle, ENT_QUOTES, 'UTF-8') ?></h1>
              <p class="text-body-secondary mb-0"><?= htmlspecialchars($dashboardDescription, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
              <span class="material-symbols-outlined align-middle me-1">check_circle</span>
              Signed in
            </span>
          </div>
        </section>

        <?php if ($dashboardRole === 'admin'): ?>
          <section id="accounts" class="row g-4 mb-1" aria-labelledby="accounts-title">
            <div class="col-12">
              <h2 id="accounts-title" class="h5 fw-bold mb-0">User accounts</h2>
            </div>
            <?php foreach (['admin' => 'Admins', 'customer' => 'Customers', 'staff' => 'Staff'] as $role => $label): ?>
              <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 h-100">
                  <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="icon-badge bg-primary-subtle text-primary flex-shrink-0">
                      <span class="material-symbols-outlined"><?= $role === 'admin' ? 'admin_panel_settings' : ($role === 'staff' ? 'badge' : 'people') ?></span>
                    </div>
                    <div>
                      <p class="text-body-secondary mb-1"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></p>
                      <p class="h4 fw-bold mb-0"><?= $accountCounts[$role] ?></p>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </section>
        <?php else: ?>
          <section class="row g-4" aria-label="Account overview">
            <div class="col-12 col-xl-7">
              <div class="card border-0 h-100">
                <div class="card-body p-4 p-lg-5">
                  <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge bg-primary-subtle text-primary flex-shrink-0">
                      <span class="material-symbols-outlined"><?= $dashboardRole === 'staff' ? 'badge' : 'waving_hand' ?></span>
                    </div>
                    <div>
                      <p class="text-body-secondary mb-2">Good to see you</p>
                      <h2 class="h4 fw-bold mb-2">Welcome to your <?= htmlspecialchars($dashboardRoleLabel, ENT_QUOTES, 'UTF-8') ?> workspace</h2>
                      <p class="text-body-secondary mb-0"><?= $dashboardRole === 'staff' ? 'Your staff account is active and ready to use.' : 'Your customer account is active and ready to use.' ?></p>
                    </div>
                  </div>
                  <hr class="my-4">
                  <dl id="account" class="row gy-3 mb-0">
                    <dt class="col-sm-4 text-body-secondary fw-normal">Account</dt>
                    <dd class="col-sm-8 mb-0 fw-semibold text-break"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt class="col-sm-4 text-body-secondary fw-normal">Account type</dt>
                    <dd class="col-sm-8 mb-0"><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($dashboardRoleLabel, ENT_QUOTES, 'UTF-8') ?></span></dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="col-12 col-xl-5">
              <div id="session" class="card border-0 h-100">
                <div class="card-body p-4 p-lg-5">
                  <h2 class="h6 fw-bold mb-3">Your session</h2>
                  <p class="text-body-secondary mb-4">Your account is currently signed in on this device.</p>
                  <div class="d-flex align-items-center gap-3 rounded-3 bg-body-tertiary p-3">
                    <div class="icon-badge bg-success-subtle text-success flex-shrink-0">
                      <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <div>
                      <p class="fw-semibold mb-1">Session active</p>
                      <p class="small text-body-secondary mb-0">Sign out from the account menu when you're finished.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($dashboardRole === 'admin'): ?>
          <section id="account" class="card border-0 mt-4">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold mb-3">Your account</h2>
              <dl class="row gy-2 mb-0">
                <dt class="col-sm-3 text-body-secondary fw-normal">Signed in as</dt>
                <dd class="col-sm-9 mb-0 fw-semibold text-break"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></dd>
                <dt class="col-sm-3 text-body-secondary fw-normal">Account type</dt>
                <dd class="col-sm-9 mb-0"><span class="badge bg-primary-subtle text-primary">Administrator</span></dd>
              </dl>
            </div>
          </section>
        <?php endif; ?>
      </div>
    </main>
    <?php require __DIR__ . '/scripts.php'; ?>
  </body>
</html>

<?php
$sidebarRole = $_SESSION['user_role'];
$dashboardLinks = [
    'admin' => [
        ['label' => 'Overview', 'icon' => 'dashboard', 'href' => 'dashboard_admin.php#overview'],
        ['label' => 'User accounts', 'icon' => 'manage_accounts', 'href' => 'dashboard_admin.php#accounts'],
        ['label' => 'My account', 'icon' => 'account_circle', 'href' => 'dashboard_admin.php#account'],
    ],
    'customer' => [
        ['label' => 'Overview', 'icon' => 'dashboard', 'href' => 'dashboard_customer.php#overview'],
        ['label' => 'My account', 'icon' => 'account_circle', 'href' => 'dashboard_customer.php#account'],
        ['label' => 'My session', 'icon' => 'verified_user', 'href' => 'dashboard_customer.php#session'],
    ],
    'staff' => [
        ['label' => 'Overview', 'icon' => 'dashboard', 'href' => 'dashboard_staff.php#overview'],
        ['label' => 'Staff account', 'icon' => 'badge', 'href' => 'dashboard_staff.php#account'],
        ['label' => 'My session', 'icon' => 'verified_user', 'href' => 'dashboard_staff.php#session'],
    ],
];
$roleLabels = ['admin' => 'Administrator', 'customer' => 'Customer', 'staff' => 'Staff'];
?>
<aside class="sidebar-wrapper" aria-label="Main navigation">
  <div class="sidebar-header">
    <div class="logo-icon">
      <img src="assets/images/logo-icon.png" class="logo-img" alt="Roksyn">
    </div>
    <div class="logo-name flex-grow-1">
      <h5 class="mb-0">Roksyn</h5>
    </div>
    <button type="button" class="sidebar-close btn border-0 bg-transparent p-0 text-body" aria-label="Close navigation">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>

  <div class="sidebar-nav" data-simplebar="true">
    <nav aria-label="<?= htmlspecialchars($roleLabels[$sidebarRole], ENT_QUOTES, 'UTF-8') ?> navigation">
      <ul class="metismenu" id="menu">
        <li class="menu-label"><?= htmlspecialchars($roleLabels[$sidebarRole], ENT_QUOTES, 'UTF-8') ?></li>
        <?php foreach ($dashboardLinks[$sidebarRole] as $link): ?>
          <li>
            <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>">
              <div class="parent-icon">
                <span class="material-symbols-outlined"><?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?></span>
              </div>
              <div class="menu-title"><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?></div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
  </div>

  <div class="sidebar-bottom dropdown dropup-center dropup">
    <button type="button" class="dropdown-toggle btn btn-link text-decoration-none text-body d-flex align-items-center px-3 gap-3 w-100 h-100" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
      <div class="user-img">
        <img src="assets/images/avatars/01.png" alt="">
      </div>
      <div class="user-info text-start text-truncate">
        <h5 class="mb-0 user-name text-truncate"><?= htmlspecialchars($_SESSION['user_email'] ?? 'User', ENT_QUOTES, 'UTF-8') ?></h5>
        <p class="mb-0 user-designation"><?= htmlspecialchars($roleLabels[$sidebarRole], ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li>
        <form method="post" action="logout.php" class="m-0">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
          <button class="dropdown-item" type="submit">
            <span class="material-symbols-outlined me-2">logout</span>
            <span>Sign out</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>

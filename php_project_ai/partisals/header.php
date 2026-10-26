<?php
$headerRoles = ['admin' => 'Administrator', 'customer' => 'Customer', 'staff' => 'Staff'];
$headerRole = $_SESSION['user_role'] ?? '';
?>
<header class="top-header">
  <nav class="navbar navbar-expand justify-content-between">
    <button type="button" class="btn btn-link text-body text-decoration-none btn-toggle-menu p-0" aria-label="Toggle navigation">
      <span class="material-symbols-outlined">menu</span>
    </button>
    <span class="d-none d-sm-inline text-body-secondary"><?= htmlspecialchars($headerRoles[$headerRole] ?? 'Account', ENT_QUOTES, 'UTF-8') ?> workspace</span>
    <span class="badge rounded-pill bg-primary-subtle text-primary"><?= htmlspecialchars($headerRoles[$headerRole] ?? 'Account', ENT_QUOTES, 'UTF-8') ?></span>
  </nav>
</header>

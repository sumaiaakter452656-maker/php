<?php
require_once '../includes/auth.php';
require_role('admin');

$total_users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$total_jobs  = $pdo->query('SELECT COUNT(*) FROM jobs')->fetchColumn();
$pending     = $pdo->query("SELECT COUNT(*) FROM jobs WHERE status = 'pending'")->fetchColumn();
$total_apps  = $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();

$page_title = 'Admin Dashboard';
require '../includes/header.php';
?>

<h3 class="mb-4">Admin Dashboard</h3>
<div class="row g-3">
    <div class="col-md-3"><div class="card card-body">মোট ইউজার<h3><?= e($total_users) ?></h3></div></div>
    <div class="col-md-3"><div class="card card-body">মোট জব<h3><?= e($total_jobs) ?></h3></div></div>
    <div class="col-md-3"><div class="card card-body">Pending জব<h3><?= e($pending) ?></h3></div></div>
    <div class="col-md-3"><div class="card card-body">মোট আবেদন<h3><?= e($total_apps) ?></h3></div></div>
</div>

<?php require '../includes/footer.php'; ?>

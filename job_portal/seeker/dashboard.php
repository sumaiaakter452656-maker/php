<?php
require_once '../includes/auth.php';
require_role('seeker');

$seeker_id = $_SESSION['seeker_id'];

$stmt = $pdo->prepare('SELECT COUNT(*) FROM applications WHERE seeker_id = ?');
$stmt->execute([$seeker_id]);
$total_apps = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE seeker_id = ? AND status = 'shortlisted'");
$stmt->execute([$seeker_id]);
$shortlisted = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM saved_jobs WHERE seeker_id = ?');
$stmt->execute([$seeker_id]);
$saved = $stmt->fetchColumn();

$page_title = 'My Dashboard';
require '../includes/header.php';
?>

<h3 class="mb-4">My Dashboard</h3>
<div class="row g-3">
    <div class="col-md-4"><div class="card card-body">আমার আবেদন<h3><?= e($total_apps) ?></h3></div></div>
    <div class="col-md-4"><div class="card card-body">Shortlisted<h3><?= e($shortlisted) ?></h3></div></div>
    <div class="col-md-4"><div class="card card-body">সেভ করা জব<h3><?= e($saved) ?></h3></div></div>
</div>

<?php require '../includes/footer.php'; ?>

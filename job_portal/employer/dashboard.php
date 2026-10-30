<?php
require_once '../includes/auth.php';
require_role('employer');

$company_id = $_SESSION['company_id'];

$stmt = $pdo->prepare('SELECT COUNT(*) FROM jobs WHERE company_id = ?');
$stmt->execute([$company_id]);
$total_jobs = $stmt->fetchColumn();

$stmt = $pdo->prepare(
    'SELECT COUNT(*) FROM applications a JOIN jobs j ON j.id = a.job_id WHERE j.company_id = ?'
);
$stmt->execute([$company_id]);
$total_apps = $stmt->fetchColumn();

$page_title = 'Employer Dashboard';
require '../includes/header.php';
?>

<h3 class="mb-4">Employer Dashboard</h3>
<div class="row g-3">
    <div class="col-md-4"><div class="card card-body">আমার জব<h3><?= e($total_jobs) ?></h3></div></div>
    <div class="col-md-4"><div class="card card-body">মোট আবেদন<h3><?= e($total_apps) ?></h3></div></div>
</div>

<?php require '../includes/footer.php'; ?>

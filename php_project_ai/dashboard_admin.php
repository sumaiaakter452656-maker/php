<?php
require_once __DIR__ . '/auth.php';
require_user_role('admin');
require_once __DIR__ . '/dbconfig.php';

$accountCounts = ['admin' => 0, 'customer' => 0, 'staff' => 0];
$result = mysqli_query($conn, 'SELECT role, COUNT(*) AS account_count FROM users GROUP BY role');
if ($result === false) {
    error_log('Admin dashboard account query failed: ' . mysqli_error($conn));
    mysqli_close($conn);
    http_response_code(500);
    exit('Unable to load the admin dashboard.');
}

while ($row = mysqli_fetch_assoc($result)) {
    if (isset($accountCounts[$row['role']])) {
        $accountCounts[$row['role']] = (int) $row['account_count'];
    }
}
mysqli_free_result($result);
mysqli_close($conn);

$dashboardRole = 'admin';
$dashboardTitle = 'Admin dashboard';
$dashboardDescription = 'An overview of your workspace and account types.';
require __DIR__ . '/partisals/dashboard_content.php';

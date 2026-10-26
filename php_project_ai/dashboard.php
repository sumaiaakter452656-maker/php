<?php
require_once __DIR__ . '/auth.php';

$roleDashboards = [
    'admin' => 'dashboard_admin.php',
    'customer' => 'dashboard_customer.php',
    'staff' => 'dashboard_staff.php',
];
$role = $_SESSION['user_role'] ?? '';

if (!isset($_SESSION['user_id']) || !is_string($role) || !isset($roleDashboards[$role])) {
    unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['user_role']);
    header('Location: index.php');
    exit;
}

header('Location: ' . $roleDashboards[$role]);
exit;

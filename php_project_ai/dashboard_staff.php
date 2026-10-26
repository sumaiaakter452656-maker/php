<?php
require_once __DIR__ . '/auth.php';
require_user_role('staff');

$dashboardRole = 'staff';
$dashboardTitle = 'Staff dashboard';
$dashboardDescription = 'Your staff account and workspace at a glance.';
require __DIR__ . '/partisals/dashboard_content.php';

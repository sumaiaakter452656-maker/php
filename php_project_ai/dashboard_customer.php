<?php
require_once __DIR__ . '/auth.php';
require_user_role('customer');

$dashboardRole = 'customer';
$dashboardTitle = 'Customer dashboard';
$dashboardDescription = 'Your account and customer workspace at a glance.';
require __DIR__ . '/partisals/dashboard_content.php';

<?php
require __DIR__ . '/session_bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/sidebar.php';
require __DIR__ . '/partials/main-content.php';
require __DIR__ . '/partials/overlays.php';
require __DIR__ . '/partials/scripts.php';

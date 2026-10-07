<?php
require __DIR__ . '/session_bootstrap.php';
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;

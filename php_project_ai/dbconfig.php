<?php

mysqli_report(MYSQLI_REPORT_OFF);

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'php_project_ai_db';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    error_log('Database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Database connection failed.');
}

if (!mysqli_set_charset($conn, 'utf8mb4')) {
    error_log('Failed to set database connection charset: ' . mysqli_error($conn));
    mysqli_close($conn);
    http_response_code(500);
    exit('Database connection failed.');
}
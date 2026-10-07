<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'php_project_copilote_db';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die('Could not create database: ' . $conn->error);
}

if (!$conn->select_db($db)) {
    die('Could not select database: ' . $conn->error);
}

$conn->set_charset('utf8mb4');

$createUsersTable = "
    CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(254) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

if (!$conn->query($createUsersTable)) {
    die('Could not create users table: ' . $conn->error);
}
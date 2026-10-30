<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "job_portal";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
<!--
// =============================================
// Database connection (PDO)
// =============================================

// htdocs এর ভেতরে তোমার প্রজেক্ট ফোল্ডারের নাম অনুযায়ী বদলাবে
define('BASE_URL', '/job_portal/');

$db_host = 'localhost';
$db_name = 'job_portal';
$db_user = 'root';
$db_pass = '';            // XAMPP তে সাধারণত ফাঁকা

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // ডেভেলপমেন্টের সময় আসল error দেখতে চাইলে: die($e->getMessage());
    die('Database connection failed.');
}
-->


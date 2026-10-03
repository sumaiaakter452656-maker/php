<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($student->delete($id)) {
    header("Location: index.php");
    exit;
}

header("Location: index.php");
exit;

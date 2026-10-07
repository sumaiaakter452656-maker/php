<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $created = $student->create([
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'address' => $_POST['address'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ]);

    if ($created) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Entry Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="top-bar">
                <h2>Student Entry Form</h2>
                <a class="btn btn-primary" href="./index.php">Back to student list</a>
            </div>

            <form action="" method="post" class="student-form">
                <div class="form-row">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter name" required>
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter address" required>
                    </div>
                    <div>
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" placeholder="Enter phone" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" name="submit">Save Student</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = $student->update($id, [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'address' => $_POST['address'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ]);

    if ($updated) {
        header("Location: index.php");
        exit;
    }
}

$row = $student->findById($id);
if (!$row) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Edit Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Student update form</h3>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Enter name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>
        <input type="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($row['email']); ?>" required><br><br>
        <input type="text" name="address" placeholder="Enter address" value="<?php echo htmlspecialchars($row['address']); ?>" required><br><br>
        <input type="text" name="phone" placeholder="Enter phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required><br><br>
        <input type="submit" name="submit" value="UPDATE"><br><br>
    </form>
    <a href="./index.php">Back to student list</a>
</body>
</html>
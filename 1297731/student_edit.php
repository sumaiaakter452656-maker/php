<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = $student->update($id, [
        'Produect_name' => $_POST['Produect_name'] ?? '',
        'Descrivtion' => $_POST['Descrivtion'] ?? '',
        'Quantity' => $_POST['Quantity'] ?? ''
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
    <title>Product Edit Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Product update form</h3>
    <form action="" method="post">
        <input type="text" name="Produect_name" placeholder="Enter product name" value="<?php echo htmlspecialchars($row['Produect_name']); ?>" required><br><br>
        <input type="text" name="Descrivtion" placeholder="Enter description" value="<?php echo htmlspecialchars($row['Descrivtion']); ?>" required><br><br>
        <input type="number" name="Quantity" placeholder="Enter quantity" value="<?php echo htmlspecialchars($row['Quantity']); ?>" required><br><br>
        <input type="submit" name="submit" value="UPDATE"><br><br>
    </form>
    <a href="./index.php">Back to product list</a>
</body>
</html>
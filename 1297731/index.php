<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);
$products = $student->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Product list</h3>
    <a href="./student_new.php">new entry</a><br><br>

    <table border="1" >
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>

        <?php foreach ($products as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['Produect_name']); ?></td>
            <td><?php echo htmlspecialchars($row['Category']); ?></td>
            <td><?php echo htmlspecialchars($row['Descrivtion']); ?></td>
            <td><?php echo htmlspecialchars($row['price']); ?></td>
            <td><?php echo htmlspecialchars($row['Quantity']); ?></td>

            <td class="action">
                <a class="icon-btn" title="Edit" href="./student_edit.php?id=<?php echo $row['id']; ?>">✏️</a>
                <a class="icon-btn danger" title="Delete product" onclick="return confirm('Are you sure you want to delete this product?')" href="./student_delete.php?id=<?php echo $row['id']; ?>">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
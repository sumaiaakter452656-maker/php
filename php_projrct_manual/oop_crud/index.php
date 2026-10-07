<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);
$students = $student->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Student list</h3>
    <a href="./student_new.php">new entry</a><br><br>

    <table border="1" >
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>email</th>
            <th>address</th>
            <th>phone</th>
            <th>action</th>
        </tr>

        <?php foreach ($students as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['address']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>

            <td class="action">
                <a class="icon-btn" title="Edit" href="./student_edit.php?id=<?php echo $row['id']; ?>">✏️</a>
                <a class="icon-btn danger" title="Delete student" onclick="return confirm('Are you sure you want to delete this student?')" href="./delete.php?id=<?php echo $row['id']; ?>">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
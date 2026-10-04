<?php
require_once "dbconfig.php";
require_once "Student.php";

$student = new Student($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $created = $student->create([
        'Produect_name' => $_POST['Produect_name'] ?? '',
        'Category' => $_POST['Category'] ?? '',
        'Descrivtion' => $_POST['Descrivtion'] ?? '',
        'price' => $_POST['price'] ?? '',
        'Quantity' => $_POST['Quantity'] ?? ''
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
    <title>Product Entry Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="top-bar">
                <h2>Product Entry Form</h2>
                <a class="btn btn-primary" href="./index.php">Back to product list</a>
            </div>

            <form action="" method="post" class="student-form">
                <div class="form-row">
                    <div>
                        <label for="Produect_name">Product Name</label>
                        <input type="text" id="Produect_name" name="Produect_name" placeholder="Enter product name" required>
                    </div>
                    <div>
                        <label for="Category">Category</label>
                        <input type="text" id="Category" name="Category" placeholder="Enter category" required>
                    </div>
                    
                    <div>
                        <label for="Descrivtion">Description</label>
                        <input type="text" id="Descrivtion" name="Descrivtion" placeholder="Enter description" required>
                    </div>
                    <div>
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" placeholder="Enter price" required>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="Quantity">Quantity</label>
                        <input type="number" id="Quantity" name="Quantity" placeholder="Enter quantity" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success" name="submit">Save Product</button>
            </form>
        </div>
    </div>
</body>
</html>
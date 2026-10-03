<?php
 session_start();
 if($_SESSION['email']!=true){
    header("location: index.php");
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome to Dashboard</h1>
    <?php 
   
    print_r($_SESSION);
    ?>
    <a href="logout.php">Logout</a>
</body>
</html>
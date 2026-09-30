<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Student Enty From</h3>
    <?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
       $name =$_POST['name'];
       $email=$_POST['email'];
       $phone=$_POST['phone'];

    }
    ?>
    <form action="">
    <input type="text" name="name" placeholder="Enter name"><br>
    <input type="text" name="email" placeholder="enter email"><br>
    <input type="text" name="phone" placeholder="enter phone"  ><br>
    <input type="submit" name="submit" value="save"  ><br>

    </form> <br>
    <a href="./index.php">Back to student list</a>
</body>
</html>
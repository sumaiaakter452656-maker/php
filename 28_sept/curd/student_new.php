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
        //data received from enty form
       $name =$_POST['name'];
       $email=$_POST['email'];
       $address=$_POST['address'];
       $phone=$_POST['phone'];
        include_once("dbconfig.php");// database connection

        // echo "INSERT INTO students
        //  (id, name, email, address, phone) VALUES (NULL, '$name', '$email', '$address', '$phone')";

        //     echo "<br>";     -pb solve jonno

        $result = $conn->query("INSERT INTO students
         (id, name, email, address, phone) VALUES (NULL, '$name', '$email', '$address', '$phone')");

        if($conn->affected_rows){
            echo "success";
        };
    }
    ?>
    <form action="" method="post">
    <input type="text" name="name" placeholder="Enter name"><br>
    <input type="text" name="email" placeholder="enter email"><br>
    <input type="text" name="address" placeholder="enter address"  ><br>
    <input type="text" name="phone" placeholder="enter phone"  ><br>
    <input type="submit" name="submit" value="save"  ><br>

    </form> <br>
    <a href="./index.php">Back to student list</a>
</body>
</html>
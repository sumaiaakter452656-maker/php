
<?php include_once("dbconfig.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Student update From</h3>
    <?php
      // Display Student Record
           $id = $_GET['id']; 
           



    if($_SERVER['REQUEST_METHOD']=='POST'){ 
        //data received from enty form
       $name =$_POST['name'];
       $email=$_POST['email'];
       $address=$_POST['address'];
       $phone=$_POST['phone'];


        // database connection

        // echo "INSERT INTO students
        //  (id, name, email, address, phone) VALUES (NULL, '$name', '$email', '$address', '$phone')";
        //     echo "<br>";     -pb solve jonno

        //update query
        $conn->query("UPDATE students SET 
        name='$name', email='$email', address='$address',phone='$phone'
        WHERE id='$id'");
        // $result = $conn->query("INSERT INTO students 
        //  (id, name, email, address, phone) VALUES (NULL, '$name', '$email', '$address', '$phone')");

        if($conn->affected_rows){
            echo "success";
        };
    }

           $data =$conn->query("SELECT * FROM students WHERE id ='$id'");
           $row = $data->fetch_object();
    ?>
    <form action="" method="post">
    <input type="text" name="name" placeholder="Enter name" value="<?php echo $row->name; ?>"><br><br>
    <input type="text" name="email" placeholder="enter email" value="<?php echo $row->email; ?>"><br><br>
    <input type="text" name="address" placeholder="enter address"  value="<?php echo $row->address;?>"> <br><br>
    <input type="text" name="phone" placeholder="enter phone"  value="<?php echo $row->phone; ?>" ><br><br>
    <input type="submit" name="submit" value="UPDATE"><br><br>
   

    </form> <br>
    <a href="./index.php">Back to student list</a>
</body>
</html>
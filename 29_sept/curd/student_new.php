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

            <form action="" method="post" class="student-form">
                <div class="form-row">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter name">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="Enter email">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Enter address">
                    </div>
                    <div>
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" placeholder="Enter phone">
                    </div>
                </div>

                <button type="submit" class="btn btn-success" name="submit">Save Student</button>
            </form>
        </div>
    </div>
</body>
</html>
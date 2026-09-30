<?php include_once('dbconfig.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
     $data = $conn->query("SELECT * FROM students");
   
     while ($row = $data->fetch_object()){
        echo $row->name . "<br>";
     }
    //var_dump($row);
    ?>
</body>
</html>
<?php 
include_once("dbconfig.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log_in_from</title>
</head>
<body>
    <h2>log_in_from</h2>
    <a href="student_file.php">new entry</a>
    <?php
    $rawdata=$conn->query("SELECT * FROM allstudents");
    ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>

        <?php   
        while($row=$rawdata->fetch_assoc()){
            ?>
        
        

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>
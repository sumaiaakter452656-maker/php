<?php
//connection with mysql
$host ="localhost";
$user="root";
$pass = "";
$db ="pwad73";
$conn = new mysqli($host,$user,$pass,$db);
if(!$conn){
  die("database connection failed : " . mysqli_connect_error());
    
}

?>
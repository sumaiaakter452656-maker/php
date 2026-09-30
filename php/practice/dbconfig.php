<?php 
$host="localhost";
$user="root";
$pass="";
$db="pwad-1234";
$conn=new mysql($host,$user,$pass,$db);
if(!$conn){
    die("database connection failed : ".mysqil connection error());
}

?>
<?php
$host="localhost";
$user="root";
$pass="";
$db="pwad731";
$conn=new mysqli("localhost","root","","pwad731");
if(!$conn) {
    die("database connection failed : " . mysqli_connect_error());
}

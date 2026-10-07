<?php
$host="localhost";
$user="root";
$pass="";
$db="pwad73";
$conn=new mysqli("localhost","root","","pwad73");
if(!$conn) {
    die("database connection failed : " . mysqli_connect_error());
}

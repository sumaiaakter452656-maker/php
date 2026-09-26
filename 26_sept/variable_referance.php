<?php
$value1 = "Hello";
$value2 =& $value1; // $valzue1 and $value2 both equal "Hello"
$value2 = "Goodbye"; // $value1 and $value2 both equal "Goodbye"
echo "value2:".$value2;
echo"<br>";
echo "value1:". $value1;

// echo $value2;
?>
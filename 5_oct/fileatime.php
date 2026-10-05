<?php
$file = '../myfile.txt';
echo fileatime($file);

$timestamp = fileatime($file);
echo "<br>";
echo date("Y m d G:i:s", $timestamp);
?>
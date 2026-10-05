<?php
// Open a text file for reading purposes
$fh = fopen('../myfile.txt', 'r');
// While the end-of-file hasn't been reached, retrieve the next line
while (!feof($fh)){
     echo fgets($fh);}
// Close the file
fclose($fh);
?>
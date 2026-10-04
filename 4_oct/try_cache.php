<?php
ini_set('display_errors', 0);
//fopen("contacts.txt", "r") 

try {
$fh = fopen("contacts.txt", "r");
if (! $fh) {
throw new Exception("Could not open the file!");
}
} catch (Exception $e) {
echo "Error (File: ".$e->getFile().", line ".
$e->getLine()."): ".$e->getMessage();
}
?>
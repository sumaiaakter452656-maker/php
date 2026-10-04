<?php
$foods = array("pasta", "steak", "fish", "potatoes");
//$food = preg_grep("/^f/", $foods);
//$food = preg_grep("/s$/", $foods);
//$food = preg_grep("/^p/", $foods);
$food = preg_grep("/^s/", $foods);
print_r($food);
?>
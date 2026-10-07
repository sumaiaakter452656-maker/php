<?php
class MyClass {
  public $color0;
  public $amount0;
}

$obj = new MyClass();
$obj->color0 = "red";
$obj->amount0 = 5;
//$copy = clone $obj;
//print_r($copy);

print_r($obj);
echo "<hr>";

$copy =clone $obj;
$copy->color0 = "blue";
$copy->amount0 = 20;

print_r($copy);
?>
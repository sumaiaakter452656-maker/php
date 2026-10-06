<?php
// Parent class
class Fruit {
  public $name0;
  public $color0;

  public function __construct($name1, $color1) {
    $this->name0 = $name1;
    $this->color0 = $color1;
  }

  public function intro() {
    echo "The fruit is $this->name0 and the color is $this->color0.<br>";
  }
}

// Strawberry is inherited from Fruit
class Strawberry extends Fruit {
  public function message() {
    echo "Am I a fruit or a berry? ";
  }
}

$strawberry = new Strawberry("Strawberry", "red");
$strawberry->intro();
$strawberry->message();
?>
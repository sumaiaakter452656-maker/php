<?php
class Fruit {
  public $name0;
  public $color0;

  function __construct($name1, $color1) {
    $this->name0 = $name1;
    $this->color0 = $color1;
  }
  function get_details() {
    echo "Name: " . $this->name0 . ". Color: " . $this->color0 .".<br>";
  }
}

//$apple = new Fruit('Apple', 'Red');
//$apple->get_details();

$apple = new Fruit('Apple', 'Red');
var_dump($apple);

//$banana = new Fruit('Banana', 'Yellow');
//$banana->get_details();
?>
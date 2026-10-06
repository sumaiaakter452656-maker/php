<?php
class Fruit {
  public $name0;
  public $color0;

  function __construct($name1, $color1) {
    $this->name0 = $name1;
    $this->color0 = $color1;

    echo "i am ready.<hr>";
  }

  function __destruct() {
    //echo "Name: " . $this->name0 . ". Color: " . $this->color0 .".<br>";
    
     echo "tata bye bye.<br>";
  }
  }


$apple = new Fruit('Apple', 'Red');
//$banana = new Fruit('Banana', 'Yellow');
?>
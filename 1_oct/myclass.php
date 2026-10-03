<?php

class MyClass {
    // property
    public $name;
    public $age;
    public $email;

    //method
    function welcome() {
       echo "hello " . $this->name . "<br>";
    }

}
 $obj1 = new MyClass;
 $obj1->name = "rahim";
 $obj1->age = 25;
 $obj1->email = "rahim@example.com";

 $obj1->welcome();

 echo "<pre>";
 var_dump($obj1);

 $obj2 = new MyClass;
 $obj2->name = "karim";
 //$obj2->age = 30;
 //$obj2->email = "karim@example.com";

 $obj2->welcome();
 var_dump($obj2);

 $obj4 = new MyClass;
 $obj4->name ="salam";
 $obj4->welcome();
 var_dump($obj4);
?>
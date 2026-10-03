<?php

class MyClass {
    // property
    public $name;
    protected $age;
    public $email;

    //method
    function welcome() {
       echo "hello " . $this->name . "<br>";
    }

}

class child_one extends MyClass {
    public $age =30;
}
 $obj1 = new MyClass;
 $obj1->name = "rahim";
 $obj1->email = "rahim@example.com";

 $obj1->welcome();
echo "<pre>";
 var_dump($obj1);
?>
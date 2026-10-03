<?php
class Employee
{
    private $name;
    private $title;
    // Getter function 
    public function getName()
    {
        return $this->name;
    }
    // Setter function
    public function setName($name)
    {
        $this->name = $name;
    }

    public function sayHello()
    {
        echo "Hi, my name is {$this->getName()}.";
    } //end of class
}
$emp1 = new Employee;

//echo $emp1->getName();
//var_dump($emp1);
$emp1->sayHello();

<?php
class student{
    public $ID;
    public $Name;
    public $Batch;
    public $Data;

    public function __construct($file) {
        $fhandle =file($file);
        $this->Data =$fhandle;
        return $this->Data;
        
    }

    public function result($ID){
        foreach($this->Data as $line){
            list($stid,$Name,$Batch,$result) = explode(",",$line);
            if($stid==$ID){
                echo "ID.$stid . Name: $Name Batch: $Batch Result: $result";
                return;
            }
        }
    }
}
$object = new student ('result.txt');
$object -> result(101)
?>
<?php 
$car =["toyta","ody","bmw"];
//print_r($car);

echo "<pre>";
array_push($car, [10,20,30],"mercedes");
print_r($car);

array_unshift($car, "tata","mercedes");
print_r($car);

$i=0;
while($i<count($car)){
    echo $car[$i]."<br>";
$i++;
}
?>

<?php 
$citys =["cumilla", "chandpur","bogura","rajshahi","borishal"];
print_r($citys);

echo "<pre>";
array_push($citys,"dinajpur","feny");
print_r($citys);

array_pop($citys );
print_r($citys);

array_shift($citys);
print_r($citys);

for ($i=0 ; $i<count($citys) ; $i++ ){
echo $citys[$i]. "<br>";
};
$i=0;
while($i<count($citys)){
    echo $citys[$i]."<br>";
$i++;
}

//foreach($citys)
?>
<?php

$score = (double) 13; // $score = 13.0

echo $score;
echo "<br>";

var_dump ($score);
echo "<br>";

$x = (array) "sumaia";
var_dump($x);

$y=(array)"farjana";
var_dump($y);

?>
<h1>type juggling</h1>
<?php 
$total=5;
$count="15abc";
//$total = $total + $count;
echo $total
?>

<?php
$val1 = "1.2e3"; // "1200"
$val2 = 2;
echo $val1 * $val2; // outputs 2400 as 1.2e3 as a float is1200
?>
<form method="post">
    Number: <input type="number" name="num">
    <button type="submit">Check</button>
</form>
<?php
if ($_POST) {
    $n = $_POST["num"];
    $isPrime = $n > 1;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) {
             $isPrime = false;
             break; 
         }
    }
    echo $isPrime ? "$n is a Prime number" : "$n is not a Prime number";
}
?>
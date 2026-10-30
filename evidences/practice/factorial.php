<form method="post">
    Enter a Number: <input type="number" name="num">
    <button type="submit">Find Factorial</button>
</form>

<?php
if ($_POST) {
    $n = $_POST["num"];
    $fact = 1;

    for ($i = 1; $i <= $n; $i++) {
        $fact = $fact * $i;
    }

    echo "Factorial of $n is: $fact";
}
?>
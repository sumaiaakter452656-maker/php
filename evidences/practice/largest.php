
<form method="post">
    Number 1: <input type="number" name="a"><br>
    Number 2: <input type="number" name="b"><br>
    Number 3: <input type="number" name="c"><br>
    Number 4: <input type="number" name="d"><br>
    Number 5: <input type="number" name="e"><br>
    <button type="submit">Find Largest</button>
</form>

<?php
if ($_POST) {
    $a = $_POST["a"];
    $b = $_POST["b"];
    $c = $_POST["c"];
    $d = $_POST["d"];
    $e = $_POST["e"];

    $largest = $a;                       // ধরে নিলাম প্রথমটাই বড়

    if ($b > $largest) $largest = $b;    // b বড় হলে largest = b
    if ($c > $largest) $largest = $c;    // c বড় হলে largest = c
    if ($d > $largest) $largest = $d;    // d বড় হলে largest = d
    if ($e > $largest) $largest = $e;    // e বড় হলে largest = e

    echo "Largest number is: " . $largest;
}
?>
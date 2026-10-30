<form method="post">
    <?php for ($i = 1; $i <= 5; $i++) echo "Number $i: <input type='number' name='n[]'><br>"; ?>
    <button type="submit">Find Largest</button>
</form>
<?php
if ($_POST) {
    $nums = $_POST["n"];
    $largest = $nums[0];
    foreach ($nums as $x) {
        if ($x > $largest) $largest = $x;
    }
    echo "Largest number is: $largest";
}
?>

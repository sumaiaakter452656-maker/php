<form method="post">
    Enter Grade: <input type="text" name="grade">
    <button type="submit">Check</button>
</form>

<?php
if ($_POST) {
    $grade = $_POST["grade"];

    if ($grade == "A") {
        echo "Excellent";
    } else if ($grade == "B") {
        echo "Good";
    } else if ($grade == "C") {
        echo "Fair";
    } else if ($grade == "D") {
        echo "Poor";
    } else if ($grade == "F") {
        echo "Failure";
    } else {
        echo "Invalid grade";
    }
}
?>
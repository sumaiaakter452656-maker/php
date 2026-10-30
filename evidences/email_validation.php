<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
    Username: <input type="text" name="username">
    <button type="submit">Submit</button>
    </form>

<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    if (strlen($username) < 4 || strlen($username) > 8) {
        echo "Username must be 4 to 8 characters long.";
    } elseif (strpos($username, '@') === false) {
        echo "Username must contain @.";
    } else {
        echo "Username is valid.";
    }
}
?>
</body>
</html>
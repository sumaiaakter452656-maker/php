<?php
$msg = "";
if ($_POST) {
    $u = $_POST["username"];
    $e = $_POST["email"];
    if (strlen($u) < 4 || strlen($u) > 8){
        $msg = "Username must be 4-8 characters";
        }
    elseif (strpos($e, "@") === false){
         $msg = "Email must contain '@'";
         }
    else{ 
        $msg = "Registration successful!";
        }
}
?>
<form method="post">
    <p><?php echo $msg; ?></p>
    Username: <input type="text" name="username"><br> <br>
    Email: <input type="text" name="email"><br> <br>
    <button type="submit">Register</button>
</form>
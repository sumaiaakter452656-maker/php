<?php  
if(isset($_POST['user_email'])&& !empty($_POST['user_email'])){
    $email = $_POST['user_email'];
        if(filter_var($email,FILTER_VALIDATE_EMAIL)==true){
            echo "<h3 class= 'valid'> Your email address $email is a valid address</h3>";
        }else{
             echo "<h3 class= 'invalid' You have put an invalid address </h3>";
        }
}
?>
<form action="" method="post">
    <p><input type="text" name="user_email" placeholder="put email address"></p>
    <input type="submit" name="submit" value="submit">
</form>
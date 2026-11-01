<?php
if(isset($_POST['email'])&& !empty($_POST['email'])){
    $email = $_POST['email'] ;
    if(filter_var($email,FILTER_VALIDATE_EMAIL)==true){
        echo" <h3 class='valid'>your email address $email is  valid </h3>" ;
    }else{
         echo" <h3 class='invalid'> put your valid email address  </h3>" ;
    }
}
?>
<form action="" method="post">
    <p><input type="text" name="email" placeholder="put your email"></p>
    <input type="password" name="password" >
    <input type="submit" name="submit" value="submit">
</form>
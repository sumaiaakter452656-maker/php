<?php
if(isset($_POST['email'])&& !empty($_POST['email'])){
    $email=$_POST['email'];
    if(filter_var($email,FILTER_VALIDATE_EMAIL)==true){
        echo <h3 class='vaild '> your email address $email is valid address</h3>;
    }else {<h3 class='invaild'> you have put a valid email address</h3>};

}
<form action="" method='post'>
<p><input type="text" name="email" placeholder="put email address"></p>
<input type="submit" name="submit" value="submit">
</form>

?>
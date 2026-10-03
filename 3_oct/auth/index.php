<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-card">
        <h1>Login</h1>
        <?php 
        if(isset($_POST['submit'])){
            extract($_POST);
            $password = md5($password);
            include_once ("dbconfig.php");
            $result =$conn->query("SELECT * FROM users WHERE email ='$email' AND password ='$password'");
            //echo "SELECT * FROM users WHERE email ='$email' AND password ='$password'";
            if($result->num_rows > 0){
                session_start();
                $_SESSION['email'] = $email;
                header("location: dashboard.php");
            }else{
                echo "<div class='message'>Login failed. Please try again.</div>";
            }
        }
        ?>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Enter your email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" > <br>
            <input type="password" name="password" placeholder="Enter your password" > <br>

            <input type="submit" class="login-btn" name="submit" value="LOGIN">
        </form>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Login Form</h1>
    <?php 
    if(isset($_POST['submit'])){
        extract($_POST);
        $password = md5($password);
        include_once ("dbconfig.php");
        $result =$conn->query("SELECT * FROM users WHERE email ='$email' AND password ='$password'");
        //echo "SELECT * FROM users WHERE email ='$email' AND password ='$password'";
        if($result->num_rows > 0){
            header("location: dashboard.php");
        }else{
            echo "<h2>login failed</h2>";
        }
    }
    ?>
    <form action=""method="post">
       <input type="email" name="email"  placeholder="Enter your email"><br><br>
       <input type="password" name="password"  placeholder="Enter your password"> <br><br>
       <input type="submit" name="submit" value="LOGIN">
    </form>
</body>
</html>
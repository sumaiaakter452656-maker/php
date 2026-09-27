<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>subscription from</h2>
    <?php 
   // print_r($_POST);

   //print_r($_GET);

   //print_r($_REQUEST);


//    $name= $_POST['name'];
//    $email= $_POST['email'];
//    echo "You have submitted: <br>";
//    echo "Name: ". $name. "<br>";
//    echo "Email: ".$email ."<br>";


//      if (isset($_POST['submit'])){
//      $name= $_POST['name'];
//      $email= $_POST['email'];

//    echo "You have submitted: <br>";
//    echo "Name: ". $name. "<br>";
//    echo "Email: ".$email ."<br>";
// }

$x = 10;
unset($x);
isset($x);
//echo ;//    pb
    ?>
    <br>
    <form action="" method="post">
    <input type="text" name="name" placeholder="enter name"><br>
    <input type="text" name="email" placeholder="enter your email"><br>
    <input type="submit" name="submit" value="subscription ">

    </form>
</body>
</html>
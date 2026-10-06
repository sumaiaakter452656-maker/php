//::scope resolution operator

<?php
class Goodbye {
  const MESSAGE = "Thank you for visiting W3Schools.com!";
}
//new Goodbye;
$abc = new Goodbye();
echo $abc::MESSAGE;

echo Goodbye::MESSAGE; // Access constant
?>
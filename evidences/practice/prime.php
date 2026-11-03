  <form action="" method="post">
    Number: <input type="number" name="number">
    <button type="submit" name="submit">Check</button>
    </form>
    <?php
    if(isset($_POST['submit'])){
        $num =$_POST['number'];

        if ($num ==1){
            echo "is not a Prime number";
        }elseif($num ==2 ){
            echo "is a Prime number";
        }else{
            for($i=2; $i<$num ;$i++){
                if($num%$i==0){
                    echo "is not a Prime number";
                    return;
                }
            } echo "is a Prime number";
        }
    }
    ?>
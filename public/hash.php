<?php
ini_set('max_execution_time', 0); 
if($_SERVER['REQUEST_METHOD']==="POST") {

    $paszaprobiti = md5($_POST['password']);

    $string = 1; // sastavljanje stringa

    $nashash = 1;
    $list = range('A', 'z');
    unset($list[29],$list[30],$list[31]);
    unset($list[26],$list[27],$list[28]);
    $numbers = range(0,9);
    $final = array_merge($list,$numbers);
    for($i=0, $c = count($final);$i<$c;$i++) {
        for($j=0;$j<$c;$j++) {
            for($k=0;$k<$c;$k++) {
                for($l=0;$l<$c;$l++) {
                    for($m=0;$m<$c;$m++) {
                        for($n=0;$n<$c;$n++) {
                            $string = $final[$i].$final[$j].$final[$k].$final[$l].$final[$m].$final[$n];
                            if(md5($string)==$paszaprobiti) {
                                echo $string; die();
                            }
                        }
                    }
                }
            }
        }
    }
}


?>




<form action="hash.php" method="post">

<input type="password" name="password">
<input type="submit" name="submit" value="posalji">

</form>
<?php
ob_start();

include ("../../private/initialize.php");
ob_start();
if(isset($_GET['send_message'])){

    
    $message = new Message($_GET, $status = "parent");
    $result = $message->createMessage();

    if($result){
        $path = "all_messages";
        redirectUser($path);
    }

}

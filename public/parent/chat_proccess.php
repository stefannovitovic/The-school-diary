<?php
ob_start();
include ("../../private/initialize.php");
if(isset($_POST['send_answer'])){

    $newMessage = new Message($_POST, $status = "parent");
    $result = $newMessage->createMessage();
    if($result){
        $path = "all_messages";
        redirectUser($path);
    }
}

?>
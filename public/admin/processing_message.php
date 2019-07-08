<?php
ob_start();

include ("../../private/initialize.php");
ob_start();
if(isset($_GET['send_message'])){
    $sender_id = $_SESSION['id'];
    $message_content = $_GET['message'];
    $recepient = $_GET['user_id'];
    $sender_name = $_SESSION['name'];
    $message = new Message($_GET,1);
    $result = $message->createMessage();
    if($result){
        header("Location: chatbox.php");
    }

}
?>
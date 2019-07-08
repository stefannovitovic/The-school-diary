<?php
include ("../../private/initialize.php");
if(isset($_POST['send_answer'])){
    $message    = $_POST['message'];
    $sender     = $_POST['user_id'];
    $teacher    = $_SESSION['id'];
    $recipient  = $_SESSION['name'];
    $newMessage = new Message($_POST, 1);
    $result = $newMessage->createMessage();
    if($result){
        $path = "all_messages";
        redirectUser($path);
    }
}
?>
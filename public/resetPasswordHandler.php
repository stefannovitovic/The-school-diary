<?php
include_once("../private/initialize.php");
if(isset($_POST['newPassword'])){
	$password = $_POST['password'];
    $password2 = $_POST['password2'];
    $token = $_POST['hash'];
    
    $message = "";
    if($password !== $password2){
    	$message = "Wrong match";
    }
    $password2 = password_hash($password2, PASSWORD_BCRYPT);
    $result = Mapper::addNewPassword($password2, $token);
    if($result){
    	Header("Location: login.php");
    }
	echo $message;
}
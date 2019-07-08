<?php
ob_start();
include("../private/initialize.php");

if(isset($_GET['logout'])) {
    session_start();
    session_destroy();
    setcookie(
        'session',
        "",
        time()-3600
    );
    setcookie(
        'timestamp',
        "",
        time()-3600
    );
    header('Location:login.php');
    die();
}

if(isset($_COOKIE['session']) && isset($_SESSION['id'])) {
    redirectByStatus();
}
if($_SERVER['REQUEST_METHOD']==="POST") {
    $user  = new User((object) $_POST);
    $user->fillAditionalData(['username']);
    $login = new Login($user);
    $login->checkUser_and_Login();

}
include '../private/styles/css/loginHTML.html';
?>

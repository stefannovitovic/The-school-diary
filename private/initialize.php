<?php

define("DB_HOST", "localhost");
define("DB_NAME", "e_dnevnik_");
define("DB_USER", "root");
define("DB_PASS", "");
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . '/public');
define("SHARED_PATH", PRIVATE_PATH . '/shared');
define("BLOCKTIMES", array(27000,30000,33600,36600,39600,42600));
const PICTURE_SIZES = array(200,500);
require_once('autoload.php');
require_once('functions.php');
Mapper::set_database();
if(strcmp('login.php',getCurrentFileName())!==0 && strcmp('forgotPassword.php',getCurrentFileName())!==0 && strcmp('resetPasswordHandler.php',getCurrentFileName())!==0) {
    $session = new Session();
    $session->startSession();
    checkStatus();
    require_once ("styles/includes/header.php");
    require_once ("styles/includes/top_nav.php");
    require_once ("styles/includes/sidebar.php");
}
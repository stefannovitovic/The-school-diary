<?php

spl_autoload_register(function ($className) {
    $path= PRIVATE_PATH . "/classes/";
    $filename=$path.$className.".php";
    if(is_readable($filename)) {
        require $filename;
    }
});
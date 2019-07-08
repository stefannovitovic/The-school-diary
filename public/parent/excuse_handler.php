<?php

ob_start();
include ("../../private/initialize.php");

if(isset($_POST['send_request']))
{
    
    $parent_id  = $_SESSION['id'];
    $class_id   = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $teacher_id = $_POST['teacher_id'];
    $excuse_text= $_POST['excuse_text'];
    $max = 1000 * 1024;
    $result = array();
    $targetDir  ='C:/wamp64/www/_egradebook/public/teacher/excuses';
        try {
            $upload = new FileUpload($targetDir);
            $upload->setMaxSize($max);
            $upload->setIdAsFileName();
            $upload->upload();
            $result = $upload->getMessages();
        
        } catch(Exception $e) {
            $result[] = $e->getMessage();
        }
    $image_name = $result['filename'];
    $result = Mapper::insertExcuseRequest($class_id, $parent_id, $student_id,  $excuse_text, $image_name, $teacher_id);
    if($result)
    {
        $_SESSION['msg'] = true;
        redirectUser("add_excuse");
    }


}


?>
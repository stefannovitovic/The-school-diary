<?php
ob_start();
include ("../../private/initialize.php");

if(isset($_GET['excuse']) && isset($_GET['class_id'])){
    $status = "excused";
    $student_id = $_GET['excuse'];
    $class_id   = $_GET['class_id'];

    $result = Mapper::updateAbsence($student_id, $class_id, $status);

    if($result){
        redirectUser("all_absences");
    }
} else{
    $status = "unexcused";
    $student_id = $_GET['unexcuse'];
    $class_id   = $_GET['class_id'];
    $result = Mapper::updateAbsence($student_id, $class_id, $status);
    if($result){
        redirectUser("all_absences");
    }
}


?>
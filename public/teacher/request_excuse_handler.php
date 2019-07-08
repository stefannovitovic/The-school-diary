<?php
ob_start();
include ("../../private/initialize.php");

    
    if(isset($_GET['status']))
    {
        $status = $_GET['status'];
        $absence_student_id = $_GET['student_id'];

        $result = Mapper::updateAbsenceStatus($absence_student_id, $status);
        if($result){
            $_SESSION['msg'] = true;
            header("Location: request_excuse_status.php");
        }else{
            $_SESSION['msg'] = false;
        }

    }


?>
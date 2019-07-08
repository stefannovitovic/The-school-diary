<?php

ob_start();
include ("../../private/initialize.php");

if(isset($_POST['send_class_info']) && !empty($_POST['send_class_info'])){
 
     $class_info = $_POST['class'];
     $students_id    = $_POST['select_student'];
    
     $students = array();
     foreach($_POST['select_student'] as $selected_student){
        $students[] =  $selected_student;
     //   return $students;
     }
     $teacher_id = $_SESSION['id'];
     $day = getCurrentDay();
     $current_block = getCurrentBlock();
     
     $absence_status = "Waiting for approval...";
     $sql = "INSERT INTO diary_of_teaching VALUES (NULL, '{$class_info}', '{$teacher_id}')";
     $db = Database::getInstance()->getConnection();
     $st = $db->prepare($sql);
     $result = $st->execute();
     $last_id = $db->lastInsertId();
     $status = "Waiting for approval";
     $date = date("d-m-Y");
     foreach($students as $student){
     $sql2 = "INSERT INTO absence_info VALUES ('{$last_id}', '{$student}', '{$status}', '{$current_block}', '{$day}', '{$date}')";
     $st = $db->prepare($sql2);
     $result2 = $st->execute();
     }
    if($result2){
        $_SESSION['msg'] = true;
        header("Location: calendar_absence.php");
    }
}


?>
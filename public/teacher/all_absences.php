<?php 
ob_start();
include ("../../private/initialize.php");


?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-center" >
                Izostanci
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>  izostanci
                </li>
            </ol>
            <div class="col-md-12">
            <table class="table table-hover">
                <tr>
                    <th>Datum izostanka</th>
                    <th>Broj casa</th>
                    <th>Naziv casa</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th></th>
                    <th></th>
                </tr>
               
                    <?php
                        
                        $teacher_id = $_SESSION['id'];
                        $results = Mapper::findAbsenceByTeacherID($teacher_id);
                        
                        foreach($results as $result){
                            $date = date_create($result->time_info);
                            echo "<tr>";
                            echo "<td>" . date_format($date, 'd-m-Y') . "</td>";
                            echo "<td>" . $result->blocks_blocks_id . "</td>";
                            echo "<td>" . $result->name . "</td>";
                            echo "<td>" . $result->students_name . "</td>";
                            echo "<td>" . $result->lastName . "</td>";
                            echo "<td><a class='btn btn-success' href='absence_handler.php?excuse={$result->absence_student_id}&class_id={$result->class_id_fk}'>Opravdano</a></td>";
                            echo "<td><a class='btn btn-danger' href='absence_handler.php?unexcuse={$result->absence_student_id}&class_id={$result->class_id_fk}'>Neopravdano</a></td>";
                            echo "</tr>";
                        }
                        

                    ?>
               
            </table>
            </div>
            
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<?php include("../../private/styles/includes/footer.php"); ?>
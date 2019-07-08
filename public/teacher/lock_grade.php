<?php 
include("includes/header.php");
?>
<?php 
include ("includes/top_nav.php");
?>
<?php  
include ("includes/sidebar.php");
?>
<?php 
include '../private/initialize.php';
include '../private/classes/Mapper.php';
Mapper::set_database();
 ?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Lock Grade
                <small></small>
            </h1>
            <ol class="breadcrumb" class="active">
                <li>
                    <i class="fa fa-plus"></i><a href="add_grade.php"> Add grade</a> 
                </li>
                <li>
                    <i class="fa fa-recycle"></i><a href="delete_grade.php"> Delete grade</a> 
                </li>
                <li class="active">
                    <i class="fa fa-key"></i> Lock grade
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
<?php include 'grade_management/lockGrade.php'; ?> 
</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php
include("includes/footer.php");

 ?>
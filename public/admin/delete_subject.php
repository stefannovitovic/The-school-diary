 <?php 
ob_start();
include ("../../private/initialize.php");
?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Delete subject
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Delete subject
                </li>
            </ol>
        </div>
    </div>
   
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <table class="table table-hover">
                <tr>
                    <th>Subject id</th>
                    <th>Subject name</th>
                </tr>
                <?php
                Mapper::set_database();
                $table_name = "subjects";
                $delete_row = Mapper::selectAllItems($table_name);
                foreach ($delete_row as $subject){
                ?>

                    <tr>
                        <td><?php  echo $subject->subjects_id; ?></td>
                        <td><?php  echo $subject->name; ?></td>
                        <td><a href = "<?php  echo "delete_subject.php?delete=" . $subject->subjects_id; ?>">Delete</a></td>
                    </tr>
                <?php }
                if(isset($_GET['delete'])){
                    $id_delete = $_GET['delete'];
                    Mapper::deleteItem($id_delete, "delete_subject.php");


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
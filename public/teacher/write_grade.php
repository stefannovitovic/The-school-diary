<?php 
ob_start();
include ("../../private/initialize.php");

if(isset($_POST['write_grade'])){
    
    $db = Database::getInstance()->getConnection();
    $group = $_POST['group_name'];
    $msql = "INSERT INTO student_group(student_group_id, name) VALUES (NULL , :name)";
    $gr = $db->prepare($msql);
    $gr->bindParam(':name', $group);

    $gr->execute();
}

?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Write a grade
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Add group
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-4">
    <form action="" method="POST">
            <div class="form-group">
            <label for="group_name">Add group name:</label>
            <input type="text" name="group_name" id="group_name" class="form-control">
            <br>
            <input type="submit" class="btn btn-primary" name="add_group" value="Add">
            </div>
    </form>
    </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<?php include("../../private/styles/includes/footer.php"); ?>
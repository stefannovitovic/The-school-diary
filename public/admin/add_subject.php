<?php 
ob_start();
include ("../../private/initialize.php");
if(isset($_POST['add_subject']) && Sanitizer::isCSRFTokenValid()){
    $db = Database::getInstance()->getConnection();
    $subject = $_POST['subject_name'];
    $sql = "INSERT INTO subjects VALUES (NULL, :name)";
    $st = $db->prepare($sql);
    $st->bindParam(':name', $subject);

    $st->execute();
}
?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Add subject
            </h1>
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <form action="" method="POST">
                    <?=Sanitizer::CSRFTokenTag()?>
                    <div class="form-group">
                    <label for="subject_name">Add subject name:</label>
                    <input type="text" name="subject_name" id="subject_name" class="form-control">
                    <br>
                    <input type="submit" class="btn btn-primary" name="add_subject" value="Add">
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
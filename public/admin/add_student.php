<?php 
//ob_start();

require ("../../private/initialize.php");

$student_group = Mapper::selectAllItems('student_group');

if(isset($_POST['submit']) && Sanitizer::isCSRFTokenValid()){
    $student = new Student($_POST);
    $student->create();


} 

?>
<div id="page-wrapper">
    <div class="container-fluid">

     <!-- Page Heading -->
     <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Add Student
                    </h1>

                </div>
            </div>

            <div class="col-lg-8">
                <form action="add_student.php" method="POST" id="form_add_user">
                    <div class="form-group">
                    <?=Sanitizer::CSRFTokenTag()?>
                            <div>
                                <label for="name">First Name:</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div>
                                <label for="lastName">Last Name:</label>
                                <input type="text" name="lastName" id="lastName" class="form-control" required>
                            </div>
                            <div>
                                <label for="email">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div>
                                <label for="student_JMBG">JMBG:</label>
                                <input type="text" name="student_JMBG" id="student_JMBG" class="form-control" required>
                            </div>
                            <div>
                                <label for="group_id">Student Group: </label>
                                <select name="group_id">
                                    <?php
                                        foreach($student_group as $group) {
                                            echo "<option value='{$group->student_group_id}'>{$group->group_year}/{$group->group_number}</option>";
                                        }
                                    ?>
                                </select>    
                            </div>

                            <div>
                                <input type="submit" name="submit" value="Add User" class="btn btn-primary">
                            </div>
                    </div>
                </form>
          
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>
<?php include("../../private/styles/includes/footer.php"); ?>



<?php 
//ob_start();

require ("../../private/initialize.php");

$student_group = Mapper::selectAllItems('student_group');
if(Sanitizer::ifPost() && Sanitizer::isCSRFTokenValid()) {
    $student = new Student($_POST);
    $_SESSION['message'] = "{$student->name} {$student->lastName}";
    $student->students_id = (int) $_POST['students_id'];
    switch ($_POST['submit']) {
        case 'Edit':
            $student->update();
            $_SESSION['message'] .= " updated";
            break;
        case 'Delete':
            $student->deleteGrades();
            $student->delete();
            $_SESSION['message'] .= " deleted";
    }
}

?>
<div id="page-wrapper">
    <div class="container-fluid">

     <!-- Page Heading -->
     <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Edit Student
                    </h1>
                </div>
            </div>
        <?=showMessage()?>
            <?php if(isset($_GET['group_id'])) {
                $student_group = Manager::createObject('StudentGroup',$_GET['group_id']);
                $student_group->fillStudents('all');
            ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <table class="table table-hover">
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Edit</th>
                                </tr>
                <?php
                foreach($student_group->students as $student) {
                ?>
                    <tr>
                        <td><?php  echo $student->name; ?></td>
                        <td><?php  echo $student->lastName; ?></td>
                        <td><a href= "edit_student.php?students_id=<?php echo $student->student_id; ?>">View Student</a></td>
                    </tr>

                <?php } ?>
                            </table> 
                        </div> 
                    </div>

            <?php
            } else if(isset($_GET['students_id'])) {
                $student = new Student($_GET);
                $student->students_id = $_GET['students_id'];
                $student->fillAditionalData(['students_id']);

                ?>

                <form action="edit_student.php" method="POST" id="form_add_user">
                    <div class="form-group">
                            <?=Sanitizer::CSRFTokenTag()?>
                            <input type="hidden" name="students_id" value="<?=$student->student_id?>">
                            <div>
                                <label for="name">First Name:</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?=$student->name?>"  required>
                            </div>
                            <div>
                                <label for="lastName">Last Name:</label>
                                <input type="text" name="lastName" id="lastName" class="form-control" value="<?=$student->lastName?>"  required>
                            </div>
                            <div>
                                <label for="email">E-mail:</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?=$student->email?>"  required>
                            </div>
                            <div>
                                <label for="student_JMBG">JMBG:</label>
                                <input type="text" name="student_JMBG" id="student_JMBG" class="form-control" value="<?=$student->student_JMBG?>"  required>
                            </div>
                            <div>
                                <label for="group_id">Student Group: </label><br>
                                <select name="group_id">
                                    <?php
                                        foreach($student_group as $group) {
                                            echo "<option value='{$group->student_group_id}'";
                                            if($group->student_group_id == $student->group_id) {
                                                echo "selected";
                                            }
                                            echo ">{$group->group_year}/{$group->group_number}</option>";
                                        }
                                    ?>
                                </select>
                                <hr>
                            </div>

                            <div>
                                <input type="submit" name="submit" value="Edit" class="btn btn-primary">
                                <input style="float:right" type="submit" name="submit" value="Delete" class="btn btn-primary">
                            </div>
                    </div>
                </form>


                <?php
                
            } else { ?>
            <div class="row">
                        <div class="col-lg-8">
                            <table class="table table-hover">
                                <tr>
                                    <th>Group Id</th>
                                    <th>Group Name</th>
                                </tr>
                                <?php
                                Mapper::set_database();
                                $row = Mapper::find_all("student_group");
                                foreach ($row as $group){
                                    ?>
                                    <tr>
                                        <td><?php  echo $group->student_group_id; ?></td>
                                        <td><?php  echo $group->group_year."-".$group->group_number; ?></td>
                                        <td></td>
                                        <td><a href= "edit_student.php?group_id=<?php echo $group->student_group_id; ?>">View Students</a></td>
                                    </tr>
                                <?php }    ?>
                            </table> 
                        </div> 
                    </div>
                    <?php } ?>
            <!-- /.row -->
        </div>
    </div>
</div>
<?php include("../../private/styles/includes/footer.php"); ?>



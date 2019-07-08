<?php 
include("../../private/initialize.php");
$semestar = Mapper::selectAllItems('semestar');
$gradetypes = Mapper::selectAllItems('grade_type');
$allGroups = Mapper::getAllGroups();

if(Sanitizer::ifPost() && Sanitizer::checkFormFields('gradeFields') && Sanitizer::isCSRFTokenValid()) {
    switch ($_POST['submit']) {
        case 'delete':
            $oldGrade = Grade::parseData($_POST['ocene']);
            $oldGrade->delete();
            break;
        case 'update':
            $oldGrade = Grade::parseData($_POST['ocene']);
            $oldGrade->delete();
            $newGrade = new Grade($_POST);
            $newGrade->fillData();
            $newGrade->create();
            break;

        case 'add':
            $newGrade = new Grade($_POST);
            $newGrade->fillData();
            $newGrade->create();
            break;
    }
}

if(isset($_GET['studentGroup'])) {
    $studentGroupId = $_GET['studentGroup'];
} else if(isset($_POST['student_group'])) {
    $studentGroupId = $_POST['student_group'];
} else {
    $day = getCurrentDay();
    $block = getCurrentBlock();
    $schedule = unserialize($_SESSION['schedule']);
    if(isset($schedule[$day][$block])) {
        $studentGroupId=$schedule[$day][$block]['group_id'];
    }
}
if(isset($studentGroupId)) {
    $student_group = Manager::createObject('StudentGroup',$studentGroupId);
    $student_group->fillStudents('all');
    $student_group->getAllSubjects();
    $sbj = Mapper::getCurrentSubjects($student_group->student_group_id);
} else $sbj = false;


?>

<div id="page-wrapper">

<div class="container-fluid">
<div class="row">
    <div class="col-lg-12">
        <form action="teacher.php" method="get">
            <label for="studentGroup">Select student group</label>
            <select name="studentGroup" class="form-control">
                <?php
                foreach($allGroups as $group) {
                    ?>
                    <option value="<?=$group->student_group_id?>";
                        <?php
                        if(isset($studentGroupId) && $group->student_group_id==$studentGroupId){
                            echo "selected";
                        }
                        ?>
                    ><?=$group->group_year?>/<?=$group->group_number?></option>
                    <?php
                }
                ?>
            </select><br>
            <button type="submit" class="btn btn-primary mb-2">Get Gradebook</button>
        </form>
    </div>
</div>
<?php if(!$sbj) {
    echo "<h1>No current class</h1>";
    die();
    }
?>
    <!-- Modal forma za unos/izmenu ocena-->
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold" id="formtitle">Izmenite ocenu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body mx-3">
                <form method="post" action="teacher.php">
                    <div class="md-form mb-5">
                        <input type="hidden" name="student_group" value="<?=$studentGroupId?>">
                        <select name='grade_type' id='gradetype'>
                            <?php
                            foreach($gradetypes as $gradetype) {
                                ?>
                                    <option value="<?=$gradetype->grade_type_id?>"><?=$gradetype->name?></option>
                                <?php
                            }
                        ?>
                        </select><br><br>
                    </div>
                    <input type="hidden" id="ocene" name="ocene" value="" required>
                    <?=Sanitizer::CSRFTokenTag()?>
                    <div class="md-form mb-4">
                        <select name='semestar' id='semestar'>
                        <?php
                      foreach($semestar as $s) {
                          ?>
                              <option value="<?=$s->semestar_id?>"><?=$s->name?></option>
                          <?php
                      }
                    ?>       
                    </select> <br><br>
                    <input type="text" name="value" id="ocena" value="0" required>
                    </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button style="float:left" class="btn btn-warning" name="submit" value="update" id="updategrade">Update grade</button>
                        <button class="btn btn-success text-center" name="submit" value="add" id="addgrade">Add grade</button>
                        <button class="btn btn-danger" name="submit" value="delete" id="deletegrade">Delete grade</button>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                    </div>
                </form>    
        </div>
    </div>
</div>

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                GradeBook
                <small><?php echo $student_group->group_year."/".$student_group->group_number;?></small>
            </h1>
        </div>
    </div>
    <!-- /.row -->
    <div style="overflow-x:auto;">
        <table style="width:100%">
            <tr>
            <!-- ovo u parent -->
                <th style="background: #ADA996;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


">Student</th>
                <?php
                foreach ($student_group->allSubjects as $sbject) {
                    echo "<th style='background: #ADA996;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #EAEAEA, #DBDBDB, #F2F2F2, #ADA996); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


;'>";
                    echo $sbject->name;
                    echo "</th>";
                }
                ?>
            </tr>
            <?php
            foreach($student_group->students as $student) {
                echo "<tr>";
                echo "<td>".$student->name." ".$student->lastName."</td>";
                foreach($student->grades as $subject) {
                    echo "<td>";
                    if(!empty($subject->semester)) {
                        foreach ($subject->semester as $semestar) {
                            foreach($semestar->grades as $grade) {
                                echo "<div style='margin-left:3px; float:left' id= ".$subject->subjects_id."|".$student->student_id."|".$grade->grade_type."|".$semestar->semestar_id."|".$grade->value." style='float:left;' onclick='editGrade(this.id,this.innerHTML)' data-placement='left' title=' ocena ".$grade->value.", ".$subject->name.",  ".$student->name." ".$student->lastName." , ".$semestar->semestar_name.", ".$grade->grade_type_name."'Tooltip on left' id='moving'";
                                if(in_array($subject->subjects_id,$sbj)) {
                                    echo "data-toggle='modal' data-target='#modalLoginForm'";
                                }
                                echo  ">".$grade->value."</div>";
                                echo "</div></div>"; // on click za change/delete
                            }
                        }
                    } if(in_array($subject->subjects_id,$sbj)) {
                        echo "<input data-toggle='modal' data-target='#modalLoginForm' name='ocene' id= ".$subject->subjects_id."|".$student->student_id."|".$student->parent_id." style='float:right' type='submit' value='+' onclick='addGrade(this.id)' >";
                    }

                    echo "</td>";
                }
                echo "</tr>";
            } ?>
        </table>
    </div>
<script type="text/javascript">


  function editGrade(id,value) {
    document.getElementById('ocene').value = JSON.stringify(id);
    document.getElementById('ocena').value = value;
    document.getElementById('formtitle').innerHTML="EDIT GRADE";
    var k = id.split("|");
    // 3. vrednost je grade_type
    gradeType = k[2];
    document.getElementById('gradetype').value=gradeType;
    // 4. vrednost je semestar_id
    semestar_id = k[3];
    document.getElementById('semestar').value=semestar_id;
      document.getElementById('addgrade').style.display= 'none' ;
      document.getElementById('updategrade').style.display= 'block' ;
      document.getElementById('deletegrade').style.display= 'block' ;
      document.getElementById('deletegrade').style.float= 'right' ;
  }

  function addGrade(id) {
      document.getElementById('ocene').value = JSON.stringify(id);
      document.getElementById('formtitle').innerHTML="ADD GRADE";
      document.getElementById('ocena').value = "";
      document.getElementById('addgrade').style.display= 'block' ;
      document.getElementById('updategrade').style.display= 'none' ;
      document.getElementById('deletegrade').style.display= 'none' ;
  }

</script>

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php
include("../../private/styles/includes/footer.php"); 
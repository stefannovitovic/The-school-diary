<?php 
include("../../private/initialize.php");
$semestar = Mapper::selectAllItems('semestar');
$gradetypes = Mapper::selectAllItems('grade_type');
$student_group = Manager::createObject('StudentGroup',$_SESSION['group_id']);
$student_group->fillStudents('one',$_SESSION['student_id']);
$student_group->getAllSubjects();
?>

<div id="page-wrapper">

<div class="container-fluid">
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
                <th>Student</th>
                <?php
                foreach ($student_group->allSubjects as $sbject) {
                    echo "<th>";
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
                                echo  ">".$grade->value."</div>";
                                echo "</div></div>"; // on click za change/delete
                            }
                        }
                    } 

                    echo "</td>";
                }
                echo "</tr>";
            } ?>
        </table>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php
include("../../private/styles/includes/footer.php"); 
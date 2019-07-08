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
           
               
                 <div class="col-md-6">
                 <table class="table table-hover">
                      <tr>
                            <th>Datum izostanka:</th>
                            <th>Broj casova:</th>
                            <th>Ime i prezime</th>
                            <th></th>
                      </tr>
                            <?php
                                $parents_id = $_SESSION['id'];
                                $results = Mapper::selectStudentAbsenceByParentID($parents_id);
                                foreach($results as $result){
                                    ?>
                                <tr>
                                <td><?php echo $result->time_info;  ?></td>
                                <td><?php echo $result->count; ?></td>
                                <td><?php echo $result->name . " " . $result->lastName; ?></td>
                                <td><a class="btn btn-success" href="add_excuse.php?class_id=<?php echo $result->class_id_fk;?>&student_id=<?php echo $result->absence_student_id; ?> &teacher_id=<?php echo $result->teachers_teachers_id; ?>&date=<?php echo $result->time_info; ?>">Opravdaj</a></td>
                                </tr>
                                    <?php
                                }
                            
                            ?>
                            </table>
                            </div>
                            
                            <div class="col-md-6">
                            <?php
                                if(isset($_GET['student_id']) && isset($_GET['class_id']))
                                   {                                
                                    $class_id = $_GET['class_id'];
                                    $student_id = $_GET['student_id'];
                                    $teachers_id = $_GET['teacher_id'];
                                    $date = $_GET['date'];
                                    ?>

                                 
                                        <form action="excuse_handler.php" class="form-group" method="POST" enctype="multipart/form-data">
                                            <p class="alert alert-info">Izabrali ste datum: <?php echo $date;  ?></p>
                                            <input type="file" name="fileToUpload" class="form-control">
                                            <br>
                                            <textarea maxlength="1024" name="excuse_text" id="" cols="30" rows="10" class="form-control" placeholder="Upisite razlog izostanka"></textarea>
                                            <p class="characters"></p>
                                            <br>
                                            <button type="submit" name="send_request" class="btn btn-success">Posalji zahtev</button>
                                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>" >
                                            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                            <input type="hidden" name="teacher_id" value="<?php echo $teachers_id; ?>">
                                        </form>

                                    <?php

                                }
                            
                                
                            ?>
                               <?php
                                    if(isset($_SESSION['msg'])){
                                ?>
                                <div class="alert alert-success">
                                    <p class="text-center">Zahtev poslat!</p>
                                </div>
                                    <?php 
                                            unset($_SESSION['msg']);
                                        } ?>
                            </div>
                
                
            </div>
            
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
    <script>
        var textarea = $("textarea");
        $(document).ready(function(){
            $('textarea').on("input", function(){
                var maxlength = $(this).attr("maxlength");
                var currentvalue = $(this).val().length;

                if(currentvalue >= maxlength){
                    $('.characters').html("You have reached maximum characters length!");
                }else{
                    $('.characters').html("Characters left: 1024/" + (maxlength-currentvalue));
                }
            })
        })

</script>
<?php include("../../private/styles/includes/footer.php"); ?>
<?php 
ob_start();
include ("../../private/initialize.php");
$unserialized_data = unserialize($_SESSION['schedule']);
$current_day = getCurrentDay();
$current_block = getCurrentBlock();

$group_id = $unserialized_data[$current_day][$current_block]['group_id'];
$students = Mapper::getStudentsByGroup($group_id);
?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-center" >
                Dnevnik rada
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Dnevnik rada
                </li>
            </ol>
            <div class="col-lg-4">
             
            <form action="send_class_info.php" method="POST">
            <div class="form-group">
         
            <h4>Izaberite ucenika koji ne prisustvuje nastavi:</h4>
            <?php
               
            
                echo "<select name='select_student[]' multiple='multiple' class='form-control' size='10'>";
                foreach ($students as $result){
                    ?>
                    <option value='<?php echo $result->students_id; ?>'><?php echo "$result->name  $result->lastName"; ?></option> </br>
                    <?php
                }
                echo "</select>";
            ?>
            </div>
            </div>
            <div class="col-lg-8">
            <?php
                if(isset($_SESSION['msg']) && $_SESSION['msg'] == true){
                    ?>
                   <div class="alert alert-success"><span class="text-center">Uspesno ste uneli cas u evidenciju!</span></div>
                    <?php
                    unset($_SESSION['msg']);
                }
            ?>
                <div class="form-group">
                    <form action="send_class_info.php" method="POST">
                        <h4>Unesite temu casa: </h4>
                        <textarea name="class" id="class" class="form-control" cols="30" rows="10" placeholder="Unesite temu casa"></textarea>
                        <br>
                        <input type="submit" value="Posalji evidenciju" name="send_class_info" class="btn btn-warning pull-right" id="send_info">
                    
                </div>
              
            </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<!-- <script>
$(document).ready(function(){
    $("#my_button").click(function(){
        var checbox_array = [];
        $.each($("input[name='student_id']:checked"), function(){
            checkbox_array.push($(this).val());
        })
        alert("My favourite class member is: " + checbox_array.join(", "));
    })
})
</script> -->

<?php include("../../private/styles/includes/footer.php"); ?>
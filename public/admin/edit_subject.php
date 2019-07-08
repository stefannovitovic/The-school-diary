<?php 
ob_start();
include ("../../private/initialize.php");
?>
<meta charset="UTF-8">
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Edit  subject
            </h1>
            
        </div>
    </div>
    <div class="row">
    <div class="col-lg-8">
    <!-- Prikaz svih predmeta u tabeli -->

    <table class="table table-hover">
    <tr>
                <th>Subject id</th>
                <th>Subject name</th>
            </tr>
         <?php
        Mapper::set_database();
        $table = "subjects";
        $row = Mapper::selectAllItems($table);
        foreach ($row as $subject){
        ?>
        
            <tr>
                <td><?php  echo $subject->subjects_id; ?></td>
                <td><?php  echo $subject->name; ?></td>
                <td><a href = "<?php  echo "edit_subject.php?edit=" . $subject->subjects_id; ?>">Edit</a></td>
            </tr>
        <?php }    ?>
      </table>

      <!-- editovanje oznacenih predmeta -->
      <?php
       if(isset($_GET['edit'])){
        $table_name = "subjects";
        $edit_id = $_GET['edit'];
        $subjectRow = Mapper::selectByID($table_name, $edit_id);

         foreach ($subjectRow as $subject){
             ?>
            <form action="" method="GET">
            <div class="form-group">
            <label for="edit_id">Subject ID:</label>
            <input type="text" name="edit_id" value="<?php echo $subject->subjects_id ?>" class="form-control">
             <label for="edit_name_subject">Edit subject:</label>
             <input type="text" name="edit_name_subject" value="<?php echo $subject->name;?>" class="form-control"><br>
            <input type="submit" class="btn btn-warning" name="edit_subject" value="Edit">
            </div>
             </form>
         <?php
         }
       }
            if(isset($_GET['edit_subject'])){
                $edit_id = $_GET['edit_id'];
                $group_name = $_GET['edit_name_subject'];
                Mapper::updateItem($group_name, $edit_id);
            }
         ?>

        
        

        

    </div>
    </div>
    <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


<?php include("../../private/styles/includes/footer.php"); ?>
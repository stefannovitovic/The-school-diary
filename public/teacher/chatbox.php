<?php 
//ob_start();
require ("../../private/initialize.php");
?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
        <div class="col-lg-12">
            <h1 class="page-header">
                Send Message 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Send Message 
                </li>
            </ol>
            </div>
        
            <div class="row">
            <div class="col-lg-12">
            <div class="col-lg-4">
                <form action="chatbox.php" method="GET">
                <?php
                    $id = $_SESSION['id'];
                    $result = Message::selectParentsByTeacherID($id);
                    echo "<br>";
                    echo "<select class='form-control' name='parent_id'>";
                        foreach ($result as $parent){
                            ?>
                            <option value="<?php echo $parent->parents_id; ?>"><?php echo $parent->firstName . " " .  $parent->lN; ?></option>;
                            <?php
                        }
                    echo "</select>";
                ?>
                <br>
                  <input type="submit" class="btn btn-success" name="submit" value="Izaberite roditelja">
                  </form>
                </div>
                 <?php

                    if(isset($_GET['submit'])){
                     
                        $recipient_id = $_GET['parent_id'];
                        $table_name = "parents";
                       $row = Mapper::selectByID($table_name, $recipient_id);
                        foreach ($row as $user){
                            ?>
                            <div class="col-lg-4">
                                <form action="processing_message.php" method="GET">
                                    <label for="description">Unesite tekst poruke:</label>
                                    <input type="text" name="user_id" class="hidden" value="<?php echo $user->parents_id; ?>" >
                                    <div class="form-group">
                                        <textarea maxlength="255" class="form-control" name="message" id="" cols="20" rows="10"></textarea>
                                   </div>
                                   <p class="characters"></p>
                                   <div class="form-group"><br>
                                        <input type="submit" value="Posalji poruku" name="send_message" class="btn btn-primary">
                                   </div>
                                </form>
                                </div>
                            <?php
                        }
                    }
                ?>
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
                    $('.characters').html("Characters left: " + (maxlength-currentvalue));
                }
            })
        })

</script>
<?php include("../../private/styles/includes/footer.php"); ?>
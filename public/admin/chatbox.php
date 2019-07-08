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
                <form action="" method="GET">
                <div class="form-group">
                <label for="choose_user">Izaberite roditelja kome zelite da posaljete poruku:</label>
                <select name="choose_user" class="form-control">
                <?php
                    $table = "parents";
                    $id = $_SESSION['id'];
                    $row = Message::selectParentsByTeacherID($id);
                    foreach ($row as $parent){
                        ?>
                            <option  value="<?php echo $parent->parents_id; ?>"><?php echo $parent->first_name;?></option>
                        <?php
                    }
                ?>
                </select>
                <br>
                <div class="form-group">
                        <input type="submit" value="Izaberi korisnika" class="btn btn-primary" name="submit">
                </div>
                </div>
                </form>
                </div>
                <?php

                    if(isset($_GET['submit'])){
                     
                        $userId = $_GET['choose_user'];
                        $table = "parents";

                         $row = Mapper::selectByID($table, $userId);
                        foreach ($row as $user){
                            ?>
                            <div class="col-lg-4">
                                <form action="processing_message.php" method="get">
                                    <label for="description">Unesite tekst poruke:</label>
                                    <input type="text" name="user_id" value="<?php echo $user->parents_id; ?>" style="visibility: hidden;">
                                    <div class="form-group">
                                        <textarea maxlength="255" class="form-control" name="message" id="" cols="20" rows="10"></textarea>
                                   </div>
                                   <div class="form-group">
                                   <p class="characters"></p>
                                        <input type="submit" value="Posalji poruku" name="send_message" class="btn btn-primary" id="sendmessage">
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
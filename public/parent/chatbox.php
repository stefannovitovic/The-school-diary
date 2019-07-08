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
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Povratak na pocetnu stranu</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>Send Message 
                </li>
            </ol>
            </div>
        
            <div class="row">
            <div class="col-lg-12">
            <div class="col-lg-4">
                
                <?php
                    $id = $_SESSION['id'];
                    $result = Message::selectTeacherByParentID($id);
                        foreach ($result as $parent){
                            ?>
                           <h3><a href="chatbox.php?answer=<?php echo $parent->teachers_id;?>">Posaljite poruku profesoru</a></h3>
                            <?php
                        }
                ?>
               
                </div>
                <?php

                    if(isset($_GET['answer'])){
                     
                        $recipient_id = $_GET['answer'];
                        $table = "teachers";

                         $row = Mapper::selectByID($table, $recipient_id);
                        foreach ($row as $user){
                            ?>
                            <div class="col-lg-4">
                                <form action="processing_message.php" method="GET">
                                    <label for="description">Unesite tekst poruke:</label>
                                    <input type="text" name="user_id" style="visibility:hidden;" value="<?php echo $recipient_id; ?>" >
                                    <div class="form-group">
                                        <textarea maxlength="255" class="form-control" name="message" id="" cols="20" rows="10"></textarea>
                                   </div>
                                   <p class="characters"></p>
                                   <div class="form-group">
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
<?php 
ob_start();
include ("../../private/initialize.php");
?>
<div id="page-wrapper">
<div class="container-fluid">
<div class="col-lg-12">
    <h1 class="page-header text-center">Answer message </h1>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-file"></i>Answer message
            </li>
        </ol>
<div class="col-lg-6">
<div class="messaging">
<?php


        if(isset($_GET['answer'])){
            $id_message = $_GET['answer'];
            $parent_id = $_SESSION['id'];
            $teacher_id = $_GET['sender'];
            // echo $id_message . "<br>";
            // echo $teacher_id . "<br>";
            // echo $parent_id . "<br>";
            $row = Message::findAllMessagesByID($teacher_id, $parent_id);
            foreach($row as $message){
                $date = date_create($message->time);
                $formated_date = date_format($date, "h:s / d-m-Y");
                if($message->sender_name == $_SESSION['username']){
                    ?>
                        <div class="incoming_msg">
                        <div class="received_msg">
                        <div class="received_withd_msg">
                        <div class="text-muted text-left pr-2"><?php echo $message->sender_name; ?></div>
                            <p><?php echo $message->message; ?></p>
                            <span class="time_date text-left"><?php echo $formated_date; ?></span>
                        </div>
                        </div>
                        </div>
                    <?php
                }else{
                    ?>
                    <div class="outgoing_msg">
                    <div class="sent_msg">
                        <div class="text-muted text-right pr-2"><?php echo $message->sender_name; ?></div>
                        <p><?php echo $message->message; ?></p>
                        <span class="time_date text-right"><?php echo $formated_date; ?></span>
                      
                    </div>
                    </div>
                    <?php
                }
             
        }
        }
        ?>
    </div>
    </div>
    <div class="col-lg-4">
               
                <form action="chat_proccess.php" method="POST">
                <input type="text" name="id" style="visibility:hidden;" value="<?php echo $id_message; ?>">
                <input type="text" name="user_id" style="visibility:hidden;" value="<?php echo $teacher_id; ?>">
                <div class="form-group">
                <textarea maxlength="255" name="message" cols="10" rows="5" class="form-control"></textarea>
                <label for="message">Maximum characters 255.</label>
                </div>
                <P class="characters"></P>
                <input type="submit" value="Send answer" name="send_answer" class="btn btn-success"> 
                </form>
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
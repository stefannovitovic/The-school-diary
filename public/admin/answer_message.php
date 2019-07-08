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
<?php


        if(isset($_GET['answer'])){
            $id_message = $_GET['answer'];
            $sender_id = $_GET['sender'];
            $user = $_SESSION['id'];
            $row = Message::findAllMessagesByID($user, $sender_id);
            foreach($row as $message){
                echo "<div id='messages_admin'>";
                echo "<div style='2px solid #ccccb3; background-color: #f1f1f1; border-radius: 5px; padding: 5px; margin: 20px;border-radius: 12px;'>";
                echo "<p style='text-align: center;font-size:18px;'>" . $message->message . "</p><br>";
                echo "<h5 ><strong>" . $message->sender_name . "</strong></h5>";
                echo "<p class='text-right'>" . $message->time . "</p>";
                
                echo "</div>";
                echo "</div>";
               
        }
        }
        ?>
        </div>
            <div class="col-lg-6">
                <div id="form-input">
                <form action="chat_proccess.php" method="POST">
                <input type="text" name="answer_id" style="visibility:hidden;" value="<?php echo $id_message; ?>">
                <input type="text" name="user_id" style="visibility:hidden;" value="<?php echo $sender_id; ?>">
                <div class="form-group">
                <textarea maxlength="255" name="message" cols="10" rows="5" class="form-control"></textarea>
                <label for="answer_text">Maximum characters 255.</label>
                </div>
                <p class="characters"></p>
                <input type="submit" value="Send answer" name="send_answer" class="btn btn-success"> 
                </form>
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
                    $('.characters').html("Characters left: " + (maxlength-currentvalue));
                }
            })
        })

</script>
<?php include("../../private/styles/includes/footer.php"); ?>
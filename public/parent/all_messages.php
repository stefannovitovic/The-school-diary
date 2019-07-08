<?php 
ob_start();
    include ("../../private/initialize.php");
?>
        
<div id="page-wrapper">

<div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                
                    <h1 class="page-header text-center">
                       Inbox
                    </h1>
                    <div class="sticky-top">
                    <div id="send_new_message" class="btn btn-success pull-right pr-1">
                    <a href="chatbox.php" rel="noopener noreferrer">Compose</a>
                    </div>
                    </div>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i>Inbox
                        </li>
                    </ol>
                </div>
            </div>
            <div class="row">
            <div class="col-lg-12">
            <div id="results">
            <table class="table table-striped">
                       <tr>
                            <th>Posiljalac</th>
                            <th>Poruka</th>
                            <th>Vreme</th>
                            <th>Brisanje poruka</th>
                            <th>Odgovor</th>
                       </tr>
                        <?php
                            
                            $id = $_SESSION['id'];
                            $name = "parent_id";
                            $row = Message::findAllMessages($id, $name);
                            foreach ($row as $message){
                                ?>
                                  <tr>
                                      <td><?php echo $message->sender_name; ?></td>
                                      <td><?php echo $message->message; ?></td>
                                      <td>Poslato: <?php echo $message->time; ?></td>
                                      <td><a href="all_messages.php?delete=<?php echo $message->messages_id; ?>">Obrisi poruku</a></td>
                                      <td><a href="answer_message.php?answer=<?php echo $message->messages_id; ?>&sender=<?php echo $message->teacher_id; ?>">Odogovori na poruku</a></td>
                                  </tr>

                                <?php
                            }

                            if(isset($_GET['delete'])){
                                $delete_id = $_GET['delete'];
                                $result = Message::delete($delete_id);

                                if($result){
                                    $path = "all_messages";
                                   redirectUser($path);
                                }else {
                                    echo "Nesto ne radi ne znam sta";
                                }
                            }

                        ?>
                  
                  </div>
            </table>
            </div>
            </div>
            <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

        
            <!-- /.navbar-collapse -->
<script>
       

       function my_function(){
                       $(document).ready(function(){
                       $("#results").load("all_messages.php #results");
                   })
               };
       setInterval("my_function()", 2000);
</script>



<?php include("../../private/styles/includes/footer.php"); ?>
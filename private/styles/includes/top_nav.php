<?php
switch($_SESSION['status']) {
    case 1:
        ?>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="grad">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Admin</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Adminko <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        
                            <a href="../login.php?logout=true"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php
        break;
    case 2:
        ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="grad">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Direktor</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                
                <li class="dropdown">
                    
                   
                            <a href="../login.php?logout=true"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                      
                </li>
            </ul>
        <?php
        break;
    case 3: //teacher
        ?>
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Teacher</a>
                </div>
                <!-- Top Menu Items -->
                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <ul class="dropdown-menu alert-dropdown">
                            <li>
                                <a href="#">Accept <span class="label label-success">Alert Badge</span></a>
                            </li>
                            <li>
                                <a href="#">Decline <span class="label label-danger">Alert Badge</span></a>
                            </li>
                            <li class="divider"></li>
                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu message-dropdown">
                            <?php
                            $id = $_SESSION['id'];
                            $name = "teacher_id";
                            $row = Message::findAllMessages($id, $name);
                            foreach ($row as $message){
                                ?>
                                <li class="message-preview">
                                    <a href="answer_message.php?answer=<?php echo $message->messages_id; ?>&sender=<?php echo $message->parent_id ?>">
                                        <div class="media">
                                                    <span class="pull-left">
                                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                                    </span>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <strong><?php echo $message->sender_name; ?></strong>
                                                </h5>
                                                <p class="small text-muted"><i class="fa fa-clock-o"></i><?php echo $message->time; ?> </p>
                                                <p><?php echo $message->message; ?></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <?php
                            }
                            ?>

                            <li class="message-footer">
                                <a href="all_messages.php">Read All New Messages</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu alert-dropdown">
                            <li>
                            <?php
                                $table_name = "excuces_requests";
                                $result = Mapper::find_all($table_name);
                                $row = count($result);
                                // OVO DA SE PREPRAVI


                            ?>
                                <a href="../../public/teacher/request_excuse_status.php">Zahtevi za opravdanja:  <span style="background-color: green;" class="badge badge-warning"> <?php echo $row; ?></span></a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                        <a href="all_absences.php">Svi izostanci</a>
                             </li>
                            
                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo $_SESSION['username']; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            
                            
                            <li>
                                <a href="../login.php?logout=true"><i class="fa fa-fw fa-power-off"></i>Log Out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
        <?php
        break;
    case 4: //parent
        ?>
                <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">Roditelj</a>
                    </div>
                    <!-- Top Menu Items -->
                    <ul class="nav navbar-right top-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                            <ul class="dropdown-menu message-dropdown">
                                <?php

                                $id = $_SESSION['id'];
                                $name = "parent_id";
                                $row = Message::findAllMessages($id, $name);
                                foreach ($row as $message){
                                    ?>

                                    <li class="message-preview">
                                        <a href="answer_message.php?answer=<?php echo $message->messages_id; ?>&sender=<?php echo $message->teacher_id ?>">
                                            <div class="media">
                                                    <span class="pull-left">
                                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                                    </span>
                                                <div class="media-body">
                                                    <h5 class="media-heading">
                                                        <strong><?php echo $message->sender_name; ?></strong>
                                                    </h5>
                                                    <p class="small text-muted"><i class="fa fa-clock-o"></i><?php echo $message->time; ?> </p>
                                                    <p><?php echo $message->message; ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <?php
                                }
                                ?>

                                <li class="message-footer">
                                    <a href="all_messages.php">Read All New Messages</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                            <ul class="dropdown-menu alert-dropdown">
                            <?php
                                $parents_id = $_SESSION['id'];
                                $results = Mapper::selectStudentAbsenceByParentID($parents_id);
                                $row = count($results);
                                ?>
                                    <li><a href="../../public/parent/add_excuse.php">Izostanci: <span style="background-color:green" class="badge badge-danger"><?php echo $row; ?></span></a></li>
                                
                                     <div class="divider"></div>
                                     <li>
                                     <?php
                                        $parent_id = $_SESSION['id'];
                                        $student_id = Mapper::getStudentByParentID($parent_id);
                                        $results = "";
                                        foreach($student_id as $student){
                                            $results = Mapper::absenceStatusByParentID($student->students_id);
                                        }
                                        $row = count($results);
                                     ?>
                                         <a href="../../public/parent/absence_status.php">Zahtevi: <span class="badge badge-info"><?php echo $row; ?></span></a>
                                     </li>
                                     <div class="divider"></div>
                                   
                                     <li>
                                    <a href="#"  class="ml-2">View All</a>
                                    </li>
                                    </li>
                                    
                            </ul>
                        </li> -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                
                                <li class="divider"></li>
                                <li>
                                    <a href="../login.php?logout=true"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
        <?php
        break;
}
?>



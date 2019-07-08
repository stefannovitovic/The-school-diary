<?php
switch($_SESSION['status']) {
    case 1: //admin
        ?>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i>Users <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="add_user.php">Add user</a>
                        </li>
                        <li>
                            <a href="update_user.php">Edit user</a>
                        </li>
                        <li>
                            <a href="delete_user.php">Delete user</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo4"><i class="fa fa-fw fa-arrows-v"></i>Students <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo4" class="collapse">
                        <li>
                            <a href="add_student.php">Add student</a>
                        </li>
                        <li>
                            <a href="edit_student.php">Edit student</a>
                        </li>
                        <!--<li>
                            <a href="delete_student.php">Delete student</a>
                        </li> -->
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i>Subjects <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo1" class="collapse">
                        <li>
                            <a href="add_subject.php">Add subjects</a>
                        </li>
                        <li>
                            <a href="edit_subject.php">Edit subjects</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo9"><i class="fa fa-fw fa-arrows-v"></i>Student Groups <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo9" class="collapse">
                        <li>
                            <a href="add_groups.php">Add group</a>
                        </li>
                        <li>
                            <a href="edit_group.php">Edit group</a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="schedule.php"><i class="fa fa-fw fa-table"></i>Manage Schedule</a>
                </li>
                <li>
                    <a href="announcement.php"><i class="fa fa-fw fa-edit"></i>Manage announcements</a>
                </li>

            </ul>
        </div>
        <?php
        break;

    case 2: //director
        ?>

        <?php
        break;

    case 3: //teacher
        ?>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="teacher.php" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i>GradeBook</a>
                </li>
                <li>
                    <a href="all_messages.php" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i>Messages</a>
                </li>
                <li>
                 
                    <a href="opendoors.php" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i>Open Doors Requests</a>
                </li>
                <li>
                    <a href="schedule.php" data-toggle="collapse" data-target="#demo2"><i class="fa fa-fw fa-arrows-v"></i>Schedule</a>
                </li>
                <li>
                    <a href="calendar_absence.php" data-toggle="collapse" data-target="#demo3"><i class="fa fa-fw fa-arrows-v"></i>Insert class info</a>
                </li>
                <li>
                    <a href="announcement.php"><i class="fa fa-fw fa-edit"></i>Send announcements</a>

                </li>
            </ul>
        </div>
        <?php
        break;

    case 4: //parent
        ?>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">

                <li>
                    <a href="parent.php"><i class="fa fa-fw fa-edit"></i>Show grades</a>
                </li>
                <li>
                    <a href="add_excuse.php" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i>Absences</a>
                </li>

                <li>
                    <a href="opendoors.php" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i>Send Open Doors Requests</a>
                </li>

               

            </ul>
        </div>
        <?php
}
?>
<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            </nav>
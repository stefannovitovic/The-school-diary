<?php 

require('../../private/initialize.php');
if (isset($_GET['delete'])){
    Mapper::deleteGroup();
}
?>
         <!-- /.navbar-collapse -->
    <div id="page-wrapper">

        <div class="container-fluid">

                <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                            Manage Groups
                            <small>Subheading</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                        </li>
                        <li class="active">
                            <i class="fa fa-file"></i> Blank Page
                        </li>
                    </ol>
                </div>
            </div>
            <!--prikaz odeljenja u tabeli-->
            <div class="row">
                <div class="col-lg-8">
                    <table class="table table-hover">
                        <tr>
                            <th>Group Id</th>
                            <th>Group Year</th>
                            <th>Group Number</th>
                        </tr>
                            <?php
                                Mapper::set_database();
                                $row = Mapper::find_all("student_group");
                                foreach ($row as $group){
                            ?>
                        <tr>
                            <td><?php  echo $group->student_group_id; ?></td>
                            <td><?php  echo $group->group_year; ?></td>
                            <td><?php  echo $group->group_number; ?></td>
                            <td></td>
                            <td><a href= "delete_group.php?delete=<?php echo $group->student_group_id; ?>">Delete</a></td>
                        </tr>
                        <?php }    ?>
                    </table>
                </div>
            </div>
        </div>
            <!-- /.container-fluid -->

    </div>
            <!-- /#page-wrapper -->
<?php include("../../private/styles/includes/footer.php"); ?>
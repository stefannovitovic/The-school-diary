<?php 
ob_start();
include ("../../private/initialize.php");


?>
<div id="page-wrapper">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-center" >
                Izostanci
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.php"> Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i>  izostanci
                </li>
            </ol>
            <?php
            if(isset($_SESSION['msg']) & isset($_SESSION['msg']) == true){
                ?>
                    <p class="alert alert-success">Uspesno ste uneli izmene!</p>
                <?php
                    unset($_SESSION['msg']);
            }


            ?>
            <div class="col-md-6">
            <table class="table table-hover">
                <tr>
                    <td>Datum izostanka</td>
                    <td>Ime ucenika</td>
                    <td>Ime roditelja</td>
                    <td></td>
                </tr>
                <?php
                    $teacher_id = $_SESSION['id'];
                    $results = Mapper::showExcuseRequest($teacher_id);
                    foreach($results as $result)
                         
                    {
                        $date = date_create($result->time_info);
                        ?>
                        <tr>
                            <td><?php echo date_format($date, "d-m-Y"); ?></td>
                            <td><?php echo $result->name . " " . $result->s_last_name; ?></td>
                            <td><?php echo $result->firstName . " " . $result->lastName; ?></td>
                            <td><a href="request_excuse_status.php?request_id=<?php echo $result->id; ?>" class="btn btn-primary">Detalji zahteva</a></td>
                        </tr>

                        <?php
                    }
                    echo "</table>";
                    echo "</div>";
                    echo "<div class='col-md-6'>";
                    if(isset($_GET['request_id']))
                    {
                        $request_id = $_GET['request_id'];
                        $sql = "SELECT * FROM excuces_requests where ID = $request_id";
                        $results = Mapper::find_by_sql($sql);
                        foreach ($results as $result){
                            ?>
                            <div class="thumbnail">
                             <img  src="excuses/<?php echo $result->picture_name; ?>"alt="">
                             </div>
                             <div class="alert alert-info">
                             <label for="excuse_info">Napomena:</label>
                             <p id="excuse_info"><?php echo $result->excuse_text; ?></p>
                             </div>
                             <a href="request_excuse_handler.php?status=excused&student_id=<?php echo $result->student_id; ?>" class="btn btn-success">Opravdano</a>
                             <a href="request_excuse_handler.php?status=unexcused&student_id=<?php echo $result->student_id; ?>" class="btn btn-danger">Neopravdano</a>
                             

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

<?php include("../../private/styles/includes/footer.php"); ?>
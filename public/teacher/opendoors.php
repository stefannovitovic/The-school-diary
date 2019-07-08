
<?php

require('../../private/initialize.php');
Mapper::set_database();

if(isset($_GET['accept'])) {
    Mapper::setRequest($_GET['accept'],1);
}
if(isset($_GET['reject'])) {
    Mapper::setRequest($_GET['reject'],0);
}

$requests = Mapper::getRequests();
?>

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Open Doors
                </h1>
            </div>
        </div>

    <?php
    if(!empty($requests)) {
    foreach($requests as $req) {
        ?>
        <div class='row '>
            <div class='col-lg-8 '>
                <h2>You have new open doors request from <?=$req->firstName?>  <?=$req->lastName?></h2>
                <p><?=$req->message?></p>
                <p>Date: <?=$req->requesttime?></p>
                <a class='btn btn-success mt-2 ' style='margin-right:5px' href='?accept=<?=$req->opendoors_id?>' role='button'>Accept</a>
                <a class='btn btn-danger' href='?reject=<?=$req->opendoors_id?>' role='button'>Reject</a>
                </div>
        </div>
        <?php
    } } else {
        echo "<h2>No new requests</h2>";
    }
    ?>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php
include("../../private/styles/includes/footer.php"); 

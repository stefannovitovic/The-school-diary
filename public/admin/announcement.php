<?php

include('../../private/initialize.php');
Mapper::set_database();

if(isset($_GET['decision'])) {
    $announcement = Manager::createObject('Announcement',$_GET['announcement_id']);
    switch($_GET['decision']) {
        case 1:
            $announcement->approved = 1; 
            //posalji obavestenje svim roditeljima
            $announcement->getParents();
            $notification = "New Announcement";
            foreach($announcement->target as $parent) {
                Mapper::addNotification($notification,$parent);
            }
            break;
        case 2:
        $announcement->approved = 2;
    }
    $announcement->update();
}

$announcements = Mapper::getAnnouncements();
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Announcements
                </h1>
            </div>
        </div>

    <?php
    if(!empty($announcements)) {
    foreach($announcements as $ann) {
        ?>
        <div class='row '>
            <div class='col-lg-8 '>
                <h2>New announcement from <?=$ann->firstName?>  <?=$ann->lastName?></h2>
                <h3><?=$ann->subject?></h3>
                <p><?=$ann->body?></p>
                <a class='btn btn-success mt-2 ' style='margin-right:5px' href='?decision=1&announcement_id=<?=$ann->announcement_id?>' role='button'>Approve</a>
                <a class='btn btn-danger' href='?decision=2&announcement_id=<?=$ann->announcement_id?>' role='button'>Reject</a>
                </div>
        </div>
        <?php
    } } else {
        echo "<h2>No new announcements</h2>";
    }
    ?>
    </div>
    <!-- /.container-fluid -->

</div>
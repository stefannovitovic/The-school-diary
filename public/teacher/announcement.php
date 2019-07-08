<?php
// teacher deo, slanje notifikacija

include('../../private/initialize.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcement = new Announcement((object) $_POST);
    $announcement->setTarget();
    $announcement->send();
    $notification = "New announcement request";
    Mapper::addNotification($notification,1);
    $message = "Announcement sent";
}
?>

<div id="page-wrapper">

    <div class="container-fluid">
    <div class="row">
            <div class="col-lg-12">
                <?php
                if(isset($message)) {
                  echo $message;
                }
                ?>
            </div>
        </div>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Send Announcement
                </h1>
            </div>
        </div>

    <div class="row">
    <div class="col-lg-8">

      <form action="announcement.php" method="POST">
        <div class="form-group">
          <label for="exampleFormControlInput1">Title:</label>
          <input type="text" class="form-control" name="subject" id="exampleFormControlInput1" placeholder="Your title" required="" oninvalid="this.setCustomValidity('Popunite ovo polje')" oninput="setCustomValidity('')">
        </div>

        <br>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Your Message:</label>
          <textarea class="form-control" name="body" id="exampleFormControlTextarea1" rows="6" placeholder="Your message" required="" oninvalid="this.setCustomValidity('Popunite ovo polje')" oninput="setCustomValidity('')"></textarea>
        </div>
        <br><br>
        <button type="submit" class="btn btn-primary mb-2" value="submit">Send announcement</button>
      </form> 
      </div>
      </div>
    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php
include("../../private/styles/includes/footer.php"); 


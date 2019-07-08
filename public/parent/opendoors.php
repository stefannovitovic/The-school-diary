<?php
require('../../private/initialize.php');  

if (isset($_POST['submit'])) {
	$dtime = date('Y-m-d', strtotime($_POST['dtime']));
	$reason = $_POST['reason'];
	if (!$dtime) {
		echo $dtime;
	}
	else{
	Mapper::set_database();
	$result = Mapper::openDoorSend($dtime,$reason);

	if ($result) {
		echo "Zakazali ste za : ".$dtime.".";
	}
	else{
		echo "Nije moguce zakazati";
	};


}
}
?>        
<meta charset="utf-8">
<!--   <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <title></title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
  <link rel="stylesheet" href="main.css"><!-- 
  <title>SB Admin - Bootstrap Admin Template</title> -->

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700" rel="stylesheet">
  <script>
  $( function() {
    $( "#datepicker" ).datepicker({
      minDate: 0,
      beforeShowDay: $.datepicker.noWeekends,
    });
  } );
  </script>   
<div class="row">
	<div class="col-lg-4 text-center">
		<form id="validation_form" method="post" action="opendoors.php">
			<div class="form-group">
				<label style='color: white;'>Datum:</label>
				<input type="text" id="datepicker" name="dtime" class="form-control"><br>
			</div>
			<div class="form-group">
				<label style='color: white;'>Razlog zakazivanja:</label>
				<textarea for="reason" rows="10" cols="50" name="reason" class="form-control" required="" maxlength="500"></textarea><br><br><br><br>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="zakaži" class="btn btn-success">
			</div>
		</form> 
	</div>
</div>
        
<script>
  $(document).ready(function() {
  $('form[id="validation_form"]').validate({
    rules: {
      dtime: 'required',
      reason: 'required',
    },
    messages: {
      dtime: 'Izaberite datum',
      reason: 'Navedite razlog zakazivanja',
    },
    submitHandler: function(form) {
      form.submit();
    }
  });

});
</script> 

</body>
</html>
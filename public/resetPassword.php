<?php

if(isset($_GET['hash'])){
	$hash = $_GET['hash'];
	
	?>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
			 <form action="resetPasswordHandler.php" method="post">
			 	<div class="warning_message"></div>
			  <div  class="form-group">
			  	<?php

				  	if(isset($message) & !empty($message)){
				  		echo "<p class='alert alert-danger'>" . $message . "</p>";
				  	}
			  	?>
			  	<input name="hash" type="text" class="hidden" value="<?php echo $hash; ?>" style="display:none;">

   			  	<label>Enter new password</label>
			    <input name="password" type="text" id="addnewpassword" class="form-control" placeholder="Enter new password">
			    <br>
			    <label>Confirm password</label>
			    <input name="password2" type="text" id="confirmpassword" class="form-control" placeholder="Confirm password">
			    <br>
			    <button name="newPassword" value="Send" type="submit" class="btn btn-primary">Send</button>
			  </div>
			  
			  
			</form>
	<?php
}else{
	// Header("Location: forgotPassword.php");
}


?>	

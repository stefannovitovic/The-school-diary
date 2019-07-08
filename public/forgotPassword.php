<?php 
include('../private/initialize.php');
if (isset($_GET['hash'])) {
	print_r($_GET);
				// Mapper::addNewPassword($user,$encpass);
			}

if($_SERVER['REQUEST_METHOD']==='POST'){
	if (isset($_POST['submit']) && !empty($_POST['username'])) {
		$user = $_POST['username'];
		$hash = Sanitizer::CSRFToken();
        $result = Mapper::updateUserToken($hash, $user);
        $result2 = Mapper::findHash($user);
        $dbhash = array_shift($result2);
		$email = Mapper::getEmailFromUser($user);
		if ($email) {
			
			mail($email,"E-gradebook login password","Please follow this link to reset password: http://localhost/_egradebook/public/resetPassword.php?hash={$dbhash['forgotpasstoken']}");

			
		}else{
			echo "error";
		}

		
	}
}

 ?>
 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <form action="forgotPassword.php" method="post">
  <div  class="form-group">
  	<label>Username</label>
    <input name="username" type="text" class="form-control" placeholder="Enter username">
    <br>
    
    <button name="submit" value="Send" type="submit" class="btn btn-primary">Send</button>
    <button id="rightButton" class="btn btn-link"><a href="login.php">back to login</a></button>
  </div>
  
  
</form>
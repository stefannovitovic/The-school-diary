<?php 
if($_SERVER['REQUEST_METHOD']==='POST'){
	if (isset($_POST['submit']) && !empty($_POST['email'])) {
		$email = $_POST['email'];
		echo $email;
		ini_set("smtp_port","26");
		mail($email, "E-gradebook login password", "Your password is 123123");
		//header("Location: http://localhost/_egradebook/public/teacher/forgotPassword.php");
	}
	else{
		echo "Unesi email";
	}
}



 ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <form action="forgotPassword.php" method="post">
  <div style="border: 3px solid black;width: 500px; height: 300px;margin: 10% 30%; padding: 50px" class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input name="email" style="width: 400px" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <br>
    <button name="submit" value="Send" type="submit" class="btn btn-primary">Send</button>
  </div>
  
  
</form>
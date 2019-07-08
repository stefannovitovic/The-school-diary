<?php 

class Login
{
	public  $username;
	private  $password;
	public $encryptedPassFromDB;

	function __construct(User $obj)
	{
		if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['login'])) 
		{
			$this->username = $obj->username;
			$this->password = $_POST['password'];
			$this->encryptedPassFromDB = $obj->password;
			$this->rememberme = $obj->rememberme;
		}
	}

	public function checkUser_and_Login()
	{
		if (password_verify($this->password, $this->encryptedPassFromDB)) {
		
		$row = Mapper::getUsernameAndPassword($this->username,$this->encryptedPassFromDB);
			if ($row == false) {
				echo "Wrong username or password";
				
			} else {
	        $args['id'] = $row->users_id;
	        $args['userStatus'] = $row->status_id;
	        $args['username']   = $row->username;
	        $session = new Session($args);
	        $session->startSession();
	        $this->redirection($_SESSION['status']);
		        
			}
		}else{
			echo "<script>alert('doesnt exist in database')</script>";
		}
	}

	public function redirection($status)
	{
		switch ($status) {
				case 1:
					header('Location: http://localhost/_egradebook/public/admin/index.php');
					break;
				case 2:
					header('Location: http://localhost/_egradebook/public/director/pick_page.php');
					break;
				case 3:
					header('Location: http://localhost/_egradebook/public/teacher/index.php');
	                break;
				case 4:
					header('Location: http://localhost/_egradebook/public/parent/parent.php');
						break;			
				default:
					header('Location: http://localhost/_egradebook/login.php');
					break;
				}
	}


}
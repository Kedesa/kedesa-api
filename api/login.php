<?php
	/**
		API for the login.
		GET phone no and password
		RETURN Status,Message 
		Status Codes;
		100 - Success
		101 - Database error
		102 - Username or password Error
		103 - Field Empty
	**/
	include ('func.php');
	

		$mobile = $_POST['mobile'];
		$pwd = $_POST['pwd'];
		
		if(empty($mobile)){
			$msg = "Mobile is Empty";
		}else if (empty($pwd)){
			$msg = "Password is Empty";
		}else{
			$mobile = trim($mobile);
			$mobile = stripcslashes($mobile);
			$mobile = strip_tags($mobile);
			$mobile = $db->real_escape_string($mobile);
			
			$pwd = trim($pwd);
			$pwd = stripcslashes($pwd);
			$pwd = strip_tags($pwd);
			$pwd = $db->real_escape_string($pwd);
			$pwd = md5($pwd);
			
			$query = $db->query("SELECT user_id, username FROM users WHERE '$mobile'=mobile AND '$pwd'=pwd ");
			if($query->num_rows == 1){
				$row = $query->fetch_object();
				$_SESSION['user_id'] = $row->user_id;
				//$_SESSION['is_loggedin'] = true;
				$status = '100';
				$message = 'User Logged in Successfully';
				echo $status;
				echo '<br>';
				echo $message;
				exit();
			}else{
				$status = '102';
				$msg = 'Username or Password Error';
				echo $status;
				echo '<br>';
				echo $msg;
				exit();
			}
			
		}
		echo $status = '103';
		echo '<br>';
		echo $msg;
	
?>
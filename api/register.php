<?php
	/**
	Statuses used
	103 - Field Empty
	104 - Passwords do not match
	105 - Account Already Exists
	106 - Message API Error
	**/
	include ('func.php');
	include ('send.php');
	
	$mobile = $_POST['mobile'];
	$pwd = $_POST['pwd'];
	$c_pwd = $_POST['c_pwd'];
	
	if(empty($mobile)){
		$msg = "Mobile is Empty";
	}else if (empty($pwd)){
		$msg = "Password is Empty";
	}else if (empty($c_pwd)){
		$msg = "Confirm Password is Empty";
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
		
		$c_pwd = trim($c_pwd);
		$c_pwd = stripcslashes($c_pwd);
		$c_pwd = strip_tags($c_pwd);
		$c_pwd = $db->real_escape_string($c_pwd);
		$c_pwd = md5($c_pwd);
		
		$query = $db->query("SELECT user_id FROM users WHERE '$mobile'=mobile");
		$ver_code = getRandomCode();
		if ($pwd != $c_pwd){
			$status = '104';
			$msg = "Passwords do not Match";
			echo $status;
			echo '<br>';
			echo $msg;
			exit();
		}else if ($query->num_rows == 1){
			$status = '105';
			$msg = "Account already exists";
			echo $status;
			echo '<br>';
			echo $msg;
			exit();
		}else{
			//Send confirmation SMS to New Member
			$from = "KEDESA"; 
			$msg =  "Hi, your kedesa verification code is ".$ver_code.". Type it to your verification screen and click verify to start using kedesa. Enjoy";
			$to = $mobile;
			
			// build HTTP URL and query
			$obj = new Sender("121.241.242.114","8080","tob-rcfoau","rcf0au","$from","$msg","$to","0","1");
			$code = $obj->Submit ();
			$code2 = explode("|",$code);
			if ($code2[0] == "1701"){
				$query = $db->query("INSERT INTO `users`(`mobile` ,`pwd`, `status`, `ver_code` ) 
				VALUES ('$mobile', '$pwd', 'new', '$ver_code')");
				
				$status = '100';
				$msg = "Step 1 Completed";
				echo $status;
				echo '<br>';
				echo $msg;
				exit();
			}else{
						
				$status = '106';
				$msg = "Message API error";
				echo $status;
				echo '<br>';
				echo $msg;
				exit();
			}
		}
		
	}
	$status = '103';
	echo $status;
	echo '<br>';
	echo $msg;
?>
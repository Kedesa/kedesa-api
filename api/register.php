<?php
	/**
	Statuses used
	103 - Field Empty
	104 - Passwords do not match
	105 - Account Already Exists
	106 - Message API Error
	107 - Wrong Verification Code
	**/
	include ('func.php');
	include ('send.php');
	
	$stage = $_POST['stage'];
	
	switch ($stage){
		case "1":
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
						VALUES ('$mobile', '$pwd', 'unverified', '$ver_code')");
						
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
		break;
		case "2":
			$ver_code = $_POST["ver_code"];
			$mobile = $_POST['mobile'];
			
			if(empty($ver_code)){
				$msg = "Mobile is Empty";
				$status = '103';
				echo $status;
				echo '<br>';
				echo $msg;
				exit();
			}
			$chk_cde = $db->query("SELECT ver_code FROM users WHERE $mobile = mobile ");
			$rows = mysqli_fetch_array($chk_cde);
			$ver_code_db = $rows['ver_code'];
			if ($ver_code != $ver_code_db){
				$status = '107';
				$msg = "Wrong Verification Code";
				echo $status;
				echo '<br>';
				echo $msg;
				exit();
			}else{
				$query = $db->query(" UPDATE users SET status = 'new' WHERE  mobile='$mobile' ");
				if ($query){
					$status = '100';
					$msg = "Verification Success";
					echo $status;
					echo '<br>';
					echo $msg;
					exit();
				}else{
					$msg = 'Database Error';
					echo '101';
					echo '<br>';
					echo $msg;
					exit();
				}
			}
		break;
		case "3":
			$mobile = $_POST['mobile'];
			$username = $_POST["username"];
			$username = $db->real_escape_string(htmlentities(trim($username)));
			$name = $_POST["name"];
			$name = $db->real_escape_string(htmlentities(trim($name)));
			$email = $_POST["email"];
			$email = $db->real_escape_string(htmlentities(trim($email)));
			$sex = $_POST["sex"];
			$sex = $db->real_escape_string(htmlentities(trim($sex)));
			$per_sign = $_POST["per_sign"];
			$per_sign = $db->real_escape_string(htmlentities(trim($per_sign)));
		
			if (empty($username)){
				$msg = 'Username is Missing';
			}else if ($name == ""){
				$msg = 'Name is Missing';
			}else if ($email == ""){
				$msg = 'Email is Missing';
			}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$msg = 'Email is not Valid';
			}else if ($sex == ""){
				$msg = 'Sex is Missing';
			}else if ($per_sign == ""){
				$msg = 'Personal Signatory is missing';
			}else{
				$query = $db->query(" UPDATE users SET username = '$username', name = '$name', email = '$email', sex = '$sex', status = 'active', per_sign = '$per_sign' WHERE  mobile='$mobile' ");
				if ($query){
					$status = '100';
					$msg = "Profile Created Successfully";
					echo $status;
					echo '<br>';
					echo $msg;
					exit();
				}else{
					$msg = 'Database Error';
					echo '101';
					echo '<br>';
					echo $msg;
					exit();
				}
			}
			$status = '103';
			echo $status;
			echo '<br>';
			echo $msg;
		break;
	}
?>
<?php
	include ('func.php');
	if (isset($_SESSION['mobile'])){
		
		$mobile = $_SESSION['mobile'];
		
		
		if (isset($_GET["ver_code"]))					{$ver_code = $_GET["ver_code"];}
		   elseif (isset($_POST["ver_code"]))		{$ver_code = $_POST["ver_code"];}
		$ver_code = $db->real_escape_string(htmlentities(trim($ver_code)));
		
		$submit = $_POST['submit'];
		
		if ($submit){
			//check db for code
			$chk_cde = $db->query("SELECT * FROM users WHERE $mobile = mobile ");
			$rows = mysqli_fetch_array($chk_cde);
			$ver_code_db = $rows['ver_code'];
			
			if ($ver_code_db == $ver_code){
				$_SESSION['is_verified'] = true;
				//Update user to verified
				$query = $db->query(" UPDATE users SET status = 'Verified' WHERE  mobile='$mobile' ");
				
				header("Location: create-profile.php");
			}else{
				$msg = $error3;
				header("Location: verify.php?msg=$msg");
			}
			
		}
	}else{
		header("Location: register.php");
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> Phone No. Verification :: KEDESA</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<link href='css/fonts.css' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div class="header-top">
			<div class="wrap"> 
				<div class="logo">
					<a href="#"><img src="images/logo.png" alt=""/></a>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
     	
		<div class="register_account">
			<div class="wrap">
				
    			<div class="col_1_of_2 span_1_of_2">	
					<h4 class="title">Verify your Phone Number</h4>
						<?php 
							if ($msg){
								echo '<div class="alert alert alert-error">'.$msg.'</div>';
							}
						?> 
					<form accept-charset="UTF-8" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
						<h5 class="sub_title">We've sent a 6 digit code via SMS to your number.<br>Enter that code below to verify your number.</h5>    		
						<input type="text" name="ver_code" required="required" placeholder="Enter your Verification Code" class="number">
						<h5 class="sub_title">Click the button below to check your status.</h5>
						<div class="clear"></div>
						<div class="signup-area">
						<input type="submit" name="submit" value="Verify">
						</div>
						<p class="terms">Verification code sent to <?php echo $mobile ?>  <a href="#">[Resend Code]</a>.</p>
						<div class="clear"></div>
						<p class="terms">*Please do not refresh this page or click the back button on your browser.</p>
					</form>
				</div>
		    	<div class="col_2_of_2 span_2_of_2">
					<img src="images/verify.png">
				</div>
    	  </div> 
        </div>
	</body>
</html>
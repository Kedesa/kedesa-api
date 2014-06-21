<?php
	include ('func.php');
	if (isset($_SESSION['is_verified']) && isset($_SESSION['mobile'])){
		$mobile = $_SESSION['mobile'];
		
		if (isset($_GET["username"]))					{$username = $_GET["username"];}
			else if (isset($_POST["username"]))		{$username = $_POST["username"];}
			$username = $db->real_escape_string(htmlentities(trim($username)));
		
		if (isset($_POST["name"]))		{$name = $_POST["name"];}
			$name = $db->real_escape_string(htmlentities(trim($name)));
			
		if (isset($_POST["email"]))		{$email = $_POST["email"];}
			$email = $db->real_escape_string(htmlentities(trim($email)));
			
		if (isset($_POST["pwd"]))		{$pwd = $_POST["pwd"];}
			$pwd = $db->real_escape_string(htmlentities(trim($pwd)));
			
		if (isset($_POST["c_pwd"]))		{$c_pwd = $_POST["c_pwd"];}
			$c_pwd = $db->real_escape_string(htmlentities(trim($c_pwd)));
			
		
		$submit = $_POST['submit'];
		
		if ($submit){
			if (empty($username)){
				$msg = 'Username is Missing';
			}else if ($name == ""){
				$msg = 'Name is Missing';
			}else if ($email == ""){
				$msg = 'Email is Missing';
			}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$msg = 'Email is not Valid';
			}else if($pwd !== $c_pwd){
				$msg = $error4;
			}else{
				$pwd = md5($pwd);
				//Update User Profile
				$_SESSION['status'] = true;
				$query = $db->query(" UPDATE users SET username = '$username', name = '$name', email = '$email', pwd = '$pwd', status = 'Active' WHERE  mobile='$mobile' ");
				
				
				if ($query){
					$msg = "You can now Login";
					header("Location: login.php?msg=$msg");
					exit();
				}
			}
			header("Location: create-profile.php?msg=$msg");
		}
	}else{
		header("Location: verify.php");
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Register | Create Profile :: KEDESA</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<link href='css/fonts.css' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div class="header-top">
			<div class="wrap"> 
				<div class="logo">
					<a href="index.php"><img src="images/logo.png" alt=""/></a>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	    <div class="clear"></div>
		<div class="register_account">
			<div class="wrap">
				<div class="col_1_of_2 span_1_of_2">
					<h4 class="title">Create your Profile</h4>
						<?php 
							if ($msg){
								echo '<div class="alert alert alert-error">'.$msg.'</div>';
							}
						?>
					<form accept-charset="UTF-8" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
						<div><input type="text" name="username" value="<?php echo $username ?>" placeholder="Username" ></div>
						<div><input type="text" name="name" value="<?php echo $name?>" placeholder="Name" ></div>
						<div><input type="text" name="email" value="<?php echo $email ?>" placeholder="Email" ></div>
						<div><input type="password" name="pwd" placeholder="Password" ></div>
						<div><input type="password" name="c_pwd" placeholder="Confirm Password""></div>
						<div class="signup-area">
						<input type="submit" name="submit" value="Submit">
						</div>
					 <p class="terms">You can edit your Profile later by clicking the <a href="#">Edit your Profile</a> link.</p>
					</form>
					  
				</div>	 
				<div class="col_2_of_2 span_2_of_2">
					<img src="images/profile.png">
				</div>
				
			  </div> 
			</div>

	</body>
</html>
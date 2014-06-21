<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();

	
	$db = new mysqli('localhost','kedesa','blessing','kedesa')or die('error with connection');
	
	//error codes
	$error1 = 'Enter Your Mobile Number';
	$error2 = 'User already Registered';
	$error3 = 'Verification Code is Wrong';
	$error4 = 'Passwords do not Match';

	
	function getRandomCode(){
		$an = "0123456789";
		$su = strlen($an) - 1;
		return substr($an, rand(0, $su), 1) .
		substr($an, rand(0, $su), 1) .
		substr($an, rand(0, $su), 1) .
		substr($an, rand(0, $su), 1) .
		substr($an, rand(0, $su), 1) .
		substr($an, rand(0, $su), 1);
	}
?>
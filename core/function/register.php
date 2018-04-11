<?php

//ini_set('display_errors', 1);

/*-------------------------------------------------------
	registration
-------------------------------------------------------*/
function user_register($user_name,$user_email,$user_password,$mysqli){
	$user_name=$mysqli->real_escape_string($user_name);
	$user_email=$mysqli->real_escape_string($user_email);
	$user_password=$mysqli->real_escape_string($user_password);
	$user_password=password_hash($user_password, PASSWORD_DEFAULT);
	
	//store the posted info onto DB
	$query="INSERT INTO
				users(user_name,user_email,user_password)
			VALUES('$user_name','$user_email','$user_password')";
	
	if($mysqli->query($query)){
		return true;
	}else{
		return false;
	}
}

?>
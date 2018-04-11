<?php

function validate_credentials($username, $password, $mysqli){
	$username = $mysqli->real_escape_string($username);
	$password = $mysqli->real_escape_string($password);
	
	$sql = "SELECT
					user_id, user_name, user_password
				FROM
					users
				WHERE
					user_name='$username'";
	$result = $mysqli->query($sql);
	
	if($result->num_rows!=1){
		return false;
	}
	
	//get password and user ID
	while($row=$result->fetch_assoc()){
		$db_hashed_pwd=$row['user_password'];
		$user_id=$row['user_id'];
	}
	
	//comfirmation of hashed password
	if(password_verify($password, $db_hashed_pwd)){
		$_SESSION['user_id']=$user_id;
		return true;
	}else{
		return false;
	}
	
}

function fetch_user_ids($user_names, $mysqli){
	foreach($user_names as $name){
		$name=$mysqli->real_escape_string($name);
	}
	
	$implode_username=implode("','", $user_names);
	$sql="SELECT
				user_id, user_name
			FROM
				users
			WHERE
				user_name
			IN
				('" . $implode_username . "')";
	$result=$mysqli->query($sql);
	
	$names=array();
	while($row=$result->fetch_assoc()){
		$names[$row['user_name']]=$row['user_id'];
	}
	return $names;
	
}
	
	
?>
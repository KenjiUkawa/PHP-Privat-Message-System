<?php

//ini_set('display_errors', 1);

function validate_credentials($user_email,$user_password,$mysqli){
	$user_email=$mysqli->real_escape_string($user_email);
	$user_password=$mysqli->real_escape_string($user_password);

	
	$sql="SELECT
				*
			FROM
				users
			WHERE
				user_email='$user_email'";
	$result=$mysqli->query($sql);
	
	if($result->num_rows!=1){
		return false;
	}
	
	//extract hashed password & user ID
	while($row=$result->fetch_assoc()){
		$db_hashed_pwd=$row['user_password'];
		$user_id=$row['user_id'];
	}
	
	//verify the hashed password
	if(password_verify($user_password,$db_hashed_pwd)){
		$_SESSION['user_id']=$user_id;
		return true;
	}else{
		return false;
	}
	
}


function fetch_user_ids($user_names,$mysqli){
	foreach ($user_names as $name){
		$name=$mysqli->real_escape_string($name);
		/*$name=trim($name);
		$name=strip_tags($name);
		$name=htmlspecialchars($name);*/
	}
	$implode_username=implode("','",$user_names);
	$sql="SELECT
				user_id, user_name
			FROM
				users
			WHERE
				user_name
			IN
				('" .$implode_username. "')";
	$result=$mysqli->query($sql);
	$names=array();
	while($row=$result->fetch_assoc()){
		$names[$row['user_name']]=$row['user_id'];
	}
	return $names;
}

?>
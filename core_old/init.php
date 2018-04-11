<?php
/*--------------------------------------------------
	include new sent message
--------------------------------------------------*/
include("{$core_path}/function/message.php");


$core_path = dirname(__FILE__);

//For security, look for a name a GET parameter get in the core/pages
$file_exists = file_exists("{$core_path}/pages/{$_GET['page']}.php");

//if a file the Get parameter ask doesn't exist, redirects to below
if(empty($_GET['page'])||$file_exists==false){
	header('HTTP/1.1 404 Not Found');
	header('Location: index.php?page=inbox');
	die();
}

//Loads the above file(?page=hogehoge) automaticlly
$include_file="{$core_path}/pages/{$_GET['page']}.php";

/*
index.php?page=inbox.php --> Load: core/pages/inbox.php
 
index.php?page=new_conversation.php --> Load: core/pages/new_conversation.php

index.php?page=spam.php --> Load: core/pages/inbox.php
*/

//redirect process if non-logged-in user access to a loged-in page
session_start();
if(empty($_SESSION['user_id'])&&$_GET['page']!='login'){
	header('HTTP/1.1 403 Forbidden');
	header('Location: index.php?page=login');
	die();
}


/*-----------------------------------------
	access to DB
-----------------------------------------*/
$host = "localhost";
$username = "root";
$password = "root";
$dbname = "private_message_system";
$mysqli = new mysqli($host, $username, $password, $dbname);
if($mysqli->connect_error){
	error_log($mysqli->connect_error);
	exit;
}

//user validity
include("{$core_path}/function/user.php");
if(isset($_POST['user_name'], $_POST['user_password'])){
	if(validate_credentials($_POST['user_name'], $_POST['user_password'], $mysqli)===true){
		header('Location: index.php?page=inbox');
	}else{
		echo "Sorry. Make sure the User Name and Password again.";
	}
}

?>
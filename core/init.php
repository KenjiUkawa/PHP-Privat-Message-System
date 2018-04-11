<?php
//ini_set('display_errors', 1);

/*--------------------------------------------------
	include new sent message
--------------------------------------------------*/
$core_path = dirname(__FILE__);

//For security, look for a name a GET parameter get in the core/pages
$file_exists = file_exists("{$core_path}/pages/{$_GET['page']}.php");

//if a file that the Get parameter ask doesn't exist, redirects to below
if(empty($_GET['page'])||$file_exists==false){
	//header('HTTP/1.1 404 Not Found');
	header('Location: index.php?page=inbox');
	//die();
}

//Loads the above file(?page=hogehoge) automaticlly
$include_file="{$core_path}/pages/{$_GET['page']}.php";

$include_header="{$core_path}/pages/header.php";

/*
index.php?page=inbox.php --> Load: core/pages/inbox.php
 
index.php?page=new_conversation.php --> Load: core/pages/new_conversation.php

index.php?page=spam.php --> Load: core/pages/inbox.php
*/

/*----------------------------------------------
	initial page
----------------------------------------------*/
//redirect process if non-logged-in user access to a loged-in or sign-up page
session_start();
if(empty($_SESSION['user_id'])&&$_GET['page']!='login'&&$_GET['page']!='signup'||empty($_SESSION['user_id'])&&$_GET['page']!='login'&&$_GET['page']!='signup'&&isset($_post['to_login_page'])){
	header('Location: index.php?page=login');
}


/*-----------------------------------------
	access to DB
-----------------------------------------*/
$host = "localhost";
$username= "root";
$password = "root";
$dbname = "private_message_system";

$mysqli = new mysqli($host, $username, $password, $dbname);
if($mysqli->connect_error){
	error_log($mysqli->connect_error);
	exit;
}


/*-----------------------------------------
	user validity
-----------------------------------------*/
include("{$core_path}/function/user.php");
if(isset($_POST['user_email'], $_POST['user_password'])){
	if(validate_credentials($_POST['user_email'], $_POST['user_password'], $mysqli)===true){
		header('Location: index.php?page=inbox');
	}else{
		echo "Sorry. Make sure the User Name and Password again.";
	}
}

include("{$core_path}/function/message.php");

/*-----------------------------------------
	sign up system for register.php
-----------------------------------------*/
include("{$core_path}/function/register.php");
//conduct below if signup has been posted
if(isset($_POST['signup'],$_POST['user_name'],$_POST['user_email'],$_POST['user_password'])){
	//Check the email already exist on DB
	if(user_register($_POST['user_name'],$_POST['user_email'],$_POST['user_password'], $mysqli)===true){
		header('Location: index.php?page=login');
	}else{
		echo "The Email Already Exists. Use Another Email, Please.";
	}
}

?>
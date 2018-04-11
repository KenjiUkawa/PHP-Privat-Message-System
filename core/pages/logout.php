<?php

//ini_set('display_errors', 1);

session_start();

/*$core_path = dirname(__FILE__);
$core_path01 = dirname($core_path);
$core_path02 = dirname($core_path01);*/


//log the user accessing to logout.php?logout out
if(isset($_GET['logout'])){
	session_destroy();
	unset($_SESSION['user']);
	header("Location: ../../index.php?page=login");
	die();
}else{
	header("Location: ../../index.php?page=login");
	die();
}

?>
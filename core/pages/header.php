<?php
/*
ob_start();
session_start();

//redirect to index.php if sessiton is not set
if(empty($_SESSION['user_id'])){
	header("Location: index.php?page=login'");
}
//extract user name from user id
$sql="SELECT
			*
		FROM
			users
		WHERE
			user_id=".$_SESSION['user_id']."";
$result=$mysqli->query($query);

if(!$result){
	print('Query Error: '.$mysqli->error);
	$mysqli->close();
	exit();
}

//extract user info
while($row=$result->fetch_assoc()){
	$user_name=$row['user_name'];
	$user_photo=$row['user_icon'];
}

//close DB
$result->close();
*/
?>

		<header>
			<div id="header_inner">
				<div id="header_logo"><a href="">LOGO</a></div>
				
				<div id="header_inner_right">
					<ul id="header_user_info">
						<li><a href="index.php?page=user_page"><i class="far fa-user-circle" aria-hidden="true"></i><p> User Name<?php //echo $user_info['user_name'] ?></p></a></li>
						<li>　|　</li>
						<li><a href="index.php?page=index"><i class="fa fa-home" aria-hidden="true"></i></a></li>
					</ul>
					
					<ul id="header_function">
						<li><a href=""><i class="fas fa-comments" aria-hidden="true"></i></a></li>
						<li><a href="index.php?page=index"><i class="fa fa-envelope" alt="Private Message"></i></a></li>
						<li><a href=""><i class="fa fa-info" aria-hidden="true"></i></a></li>
					</ul>
					
					<ul id="other_options">
						<li><a href="index.php?page=preference"><i class="fa fa-sliders-h" aria-hidden="true"></i></a></li>
						<li><a href="core/pages/logout.php?logout"><i class="fas fa-sign-out-alt" aria-hidden="true"></i></a></li>
					</ul>
				</div>
				
			</div><!-- div id="header_inner" -->
		</header>
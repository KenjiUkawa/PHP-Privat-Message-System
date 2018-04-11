<!---------------------------------------
	message form
----------------------------------------->
<form action="" method="post">
	<input type="text" name="to" placeholder="To" value="<?php if(isset($_POST['to'])){echo htmlentities($_POST['to']);} ?>">
	<br>
	<input type="text" name="subject" placeholder="Subject" value="<?php if(isset($_POST['subject'])){echo htmlentities($_POST['subject']);} ?>">
	<br>
	<textarea name="body" value="<?php if(isset($_POST['body'])){echo htmlentities($_POST['body']);} ?>"></textarea>
	<br>
	<input type="submit" value="Submit">
</form>

<?php
/*---------------------------------------
	validation to new message form
---------------------------------------*/

//excute the below when clicking the submit button
if(isset($_POST['to'], $_POST['subject'], $_POST['body'])){
	$errors=array();
	//if missing a receiver
	if(empty($_POST['to'])){
		$errors[]="Receiver is Missing.";
	}else if(preg_match('#^[a-z, ]+$#i', $_POST['to']===0)){
		$errors[]="Wrong Receiver.";
	}else{
		//processing a message to multi users from below
		//separate each user name with ","
		$user_names=explode(',', $_POST['to']);
		foreach($user_names as $name){
			$name=trim($name);
		}
		$user_ids=fetch_user_ids($user_names, $mysqli);
		if(count($user_ids)!==count($user_names)){
			$errors[]="Can not find the following user: ".implode(', ', array_diff($user_names, array_keys($user_ids)));
		}
	}
	
	if(empty($_POST['subject'])){
		$errors[]="Subject is Missing.";
	}
	if(empty($_POST['body'])){
		$errors[]="Message is Missing.";
	}
	//if no errors
	if(empty($errors)){
		create_conversation(array_unique($user_ids), $_POST['subject'], $_POST['body'], $mysqli);
	}
	
}

if(isset($errors)){
	if(empty($errors)){
		//Show success message
		echo 'Successfuly Sent'.'<a href="index.php?page=inbox">Back to inbox</a>';
	}else{
		foreach($errors as $error){
			echo $error;
		}
	}
}

?>

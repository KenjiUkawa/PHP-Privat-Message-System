<?php

ini_set('display_errors', 1);

/*-----------------------------------------------
	detail page of mail front end
-----------------------------------------------*/
$errors=array();

//return true or false for errors
$valid_conversation=(isset($_GET['conversation_id']) && validate_conversation_id($_GET['conversation_id'], $mysqli));

$subject=array();
if($valid_conversation===false){
	$errors[]="ID Error.";
}else{
	/*-- get current subject --*/
	$subject=get_current_subject($_GET['conversation_id'],$mysqli);
}
/*-------- validation for reply form --------*/
if(isset($_POST['message'])){
	if(empty($_POST['message'])){
		$errors[]="メッセージを入力してください。";
	}
	if(empty($errors)){
		add_conversation_message($_GET['conversation_id'],$_POST['message'],$mysqli);
	}
}


/*------- prevent own replied message being unread -------*/
if($valid_conversation){
	if(isset($_POST['message'])){
		update_conversation_last_view($_GET['conversation_id'],$mysqli);
		$messages=fetch_conversation_messages($_GET['conversation_id'],$mysqli);
	}else{
		$messages=fetch_conversation_messages($_GET['conversation_id'], $mysqli);
		update_conversation_last_view($_GET['conversation_id'], $mysqli);
	}
	
//load header
include($include_header);
?>

	<section id="view_conversation">
	
		<h1>お友達とのメッセージ内容</h1>
		<h2><?php echo $subject; ?></h2>

<?php

	if(isset($errors)){
		foreach($errors as $error){
			echo '<div class="errors"><i class="fas fa-exclamation-circle fa-2x"></i><p>'.$error.'</p></div><br>';
		}
	}

?>

		<!------- reply form ------->
		<form action="" method="post">
			<textarea name="message"></textarea>
			<label>
				<i class="fas fa-reply"></i>
				<p>&nbsp;返信</p>
				<input type="submit" value="返信" class="input_hide">
			</label>
		</form>
		
		<div class="message_history_container">
<?php
	foreach($messages as $message){ ?>
		<div class="massage_container<?php if($message['message_unread']){echo 'unread';} ?>">
			<ul>
				<li class="user_icon"><?php echo $user_icon ?></li>
				<li class="user_name"><?php echo $message['user_name'] ?></li>
				<li class="post_date"><?php echo date('y/m/d H:i:s',$message['message_date']) ?></li>
			</ul>
			<p><?php echo $message['message_text'] ?></p>
		</div>
<?php } //end of foreach
} //end of if
?>
		</div>
	</section>
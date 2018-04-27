<?php

ini_set('display_errors', 1);

/*-----------------------------------------------
	detail page of mail front end
-----------------------------------------------*/
$errors=array();

//return true or false for errors
$valid_conversation=(isset($_GET['conversation_id']) && validate_conversation_id($_GET['conversation_id'], $mysqli));

if($valid_conversation===false){
	$errors[]="ID Error.";
}else{
	/*-- get current subject --*/
	$subject= get_current_subject($_GET['conversation_id'],$mysqli);
}


/*-------- issue a token if coming from inbox page --------*/
$previous_page=$_SERVER['HTTP_REFERER'];
if(strstr($previous_page,'index.php?page=inbox')){
	$token=rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
	$post_token=$token;
	$_SESSION['token'] = $token;
}

/*-------- validation for reply form --------*/
if(isset($_POST['message'])){
	if(empty($_POST['message'])){
		$errors[]="メッセージを入力してください。";
	}
		
	/*-------- prevent duplicate reply by token --------*/
	//get a hidden token posted
	$post_token=isset($_POST['token']) ? $_POST['token'] : '';
	//get a token straged on seeeion
	$session_token=isset($_SESSION['token']) ? $_SESSION['token'] : '';
	
	//compare tokens
	if($post_token!=''&&$session_token===$post_token){
		
		// insert conversation onto DB if corresponed with each other
		add_conversation_message($_GET['conversation_id'],$_POST['message'],$mysqli);
		
	}else{
		$errors[]="不正な処理です。";
		unset($_SESSION['token']);
		sleep(3);
		header('Location: index.php?page=inbox');
	}
	
	
	//issue another token to prevent a duplicate massage
	$token = rtrim(base64_encode(openssl_random_pseudo_bytes(32)),'=');
	//storage the token above
	$_SESSION['token'] = $token;

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
	
	//var_dump($messages);

	
//load header
include($include_header);
?>

	<section id="view_conversation">
	
		<h1>お友達とのメッセージ内容</h1>
		<h2 class="text_align_left"><?php echo $subject; ?></h2>

<?php

	if(isset($errors)){
		foreach($errors as $error){
			echo '<div class="errors"><i class="fas fa-exclamation-circle fa-2x"></i><p>'.$error.'</p></div><br>';
		}//end of foreach($errors as $error)
	}//end of if(isset($errors))
	
?>

		<!------- reply form ------->
		<form action="" method="post">
			<textarea name="message" placeholder="お友達のメッセージに返信しよう。"></textarea>
			<input type="hidden" name="token" value="<?=$token; ?>">
			<label>
				<i class="fas fa-reply"></i>
				<p>&nbsp;返信</p>
				<input type="submit" value="返信" class="input_hide">
			</label>
		</form>
		
		<div class="message_history_container">
<?php
	foreach($messages as $message){ ?>
		<div class="massage_container <?php if($conversation['conversation_unread']){ echo 'unread'; } ?> <?php
			/*-- own message or not --*/
			if($_SESSION['user_id']===$message['user_id']){ echo 'own_user_id';
			}else{ echo 'other_user_id';}
		?>">
			<ul>
				<li class="user_icon"><?php if(empty($message['user_icon'])){
											echo '<i class="far fa-user-circle" aria-hidden="true"></i>';
										}else{
											echo '<img src="{$core_path}/imgs/user_icons/'.$message['user_icon'].'" alt="User Icon"/>';
									} ?>
					
				</li>
				<li class="user_name"><?php echo $message['user_name'] ?></li>
				<li class="post_date"><small><?php echo date('y/m/d H:i:s',$message['message_date']) ?></small></li>
			</ul>
			<p class="text_align_left"><?php echo $message['message_text'] ?></p>
		</div>
<?php } //end of foreach($messages as $message)
} //end of if($valid_conversation)
?>
		</div>
	</section>
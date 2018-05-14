<?php
//ini_set('display_errors', 1);

/*---------------------------------------
	validation to new message form
---------------------------------------*/

//excute the below when clicking the submit button
if(isset($_POST['to'], $_POST['subject'], $_POST['body'])){
	$errors=array();
	//if missing a receiver
	if(empty($_POST['to'])){
		$errors[]="友達のユーザー名を入力してください。";
	}elseif(preg_match('#^[a-z, ]+$#i', $_POST['to']===0)){
		$errors[]="友達のユーザー名が間違っています。";
	}else{
		//processing a message to multi members from below
		//separate each user name with ","
		$user_names=explode(',', $_POST['to']);
		foreach($user_names as $name){
			$name=trim($name);
		}
		//check if user exists
		$user_ids=fetch_user_ids($user_names, $mysqli);
		if(count($user_ids)!==count($user_names)){
			$errors[]="次の友達が見つかりません。 ".implode(', ', array_diff($user_names, array_keys($user_ids)));
		}
	}
	
	//the other errors
	if(empty($_POST['subject'])&&empty($_POST['body'])){
		$errors[]="タイトルと、メッセージを入力してください。";
	}elseif(empty($_POST['subject'])){
		$errors[]="タイトルを入力してください。";
	}elseif(empty($_POST['body'])){
		$errors[]="メッセージを入力してください。";
	}
	
	//send new message if no errors
	if(empty($errors)){
		create_conversation(array_unique($user_ids), $_POST['subject'], $_POST['body'], $mysqli);
		$success_message= 'メッセージを送信しました。';
	}
	
}



//load header
include($include_header);
?>

<!---------------------------------------
	new message form
----------------------------------------->
<section id="new_conversation">
	
	<h1>新しいメッセージ</h1>
	<h2>プライベートメッセージの送信ができます</h2>
	
	<?php
		if(isset($errors)){
			foreach($errors as $error){
				echo '<div class="errors"><i class="fas fa-exclamation-circle fa-2x"></i><p>'.$error.'</p></div><br>';
			}
		}elseif(isset($success_message)){
			//Show success message
			echo '<div class="success_message"><i class="fas fa-check fa-2x"></i>'.$success_message.'</div>';
		}
	?>
	
	<form action="" method="post">
		<input type="text" name="to" placeholder="友達のユーザー名" value="<?php if(isset($_POST['to'])) { echo htmlentities($_POST['to']); } ?>">
		<br>
		<input type="text" name="subject" placeholder="タイトル" value="<?php if(isset($_POST['subject'])) { echo htmlentities($_POST['subject']); } ?>">
		<br>
		<textarea name="body" value="<?php if(isset($_POST['body'])) { echo htmlentities($_POST['body']); } ?>" placeholder="友達にメッセージを送ろう"></textarea>
		<br>
		<label>
			<i class="fab fa-telegram-plane"></i>
			<p>&nbsp;送信</p>
			<input type="submit" value="送信" class="input_hide">
		</label>
	</form>
</section>
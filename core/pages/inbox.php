<?php

//ini_set('display_errors', 1);

//function to delete and validatin for delete button
$errors=array();
if(isset($_GET['delete_conversation'])){
	if(validate_conversation_id($_GET['delete_conversation'],$mysqli)===false){
		$errors[]="Delete ID Error.";
	}
	if(empty($errors)){
		delete_conversation($_GET['delete_conversation'],$mysqli);
	}
}


$conversations=fetch_conversation_summery($mysqli);
/*-- display an error message --*/
if(empty($conversations)){
	$errors[]="メッセージはありません。";
}


		
//load header
include($include_header);
		
?>

<section id="inbox">
	
	<h1>受信ボックス</h1>
	<h2>プライベートメッセージの一覧です</h2>

	<div class="inner_container">
		<div id="new_message">
			<a href="index.php?page=new_conversation" alt="New Message"><i class="fas fa-pencil-alt"></i>&nbsp;メッセージを書く</a>
		</div>
		
<?php
		
	if(!empty($errors)){
		foreach($errors as $error){
			echo '<div class="no_messages">'.$error.'</div>';
		}
	}
		
	foreach($conversations as $conversation){ ?>

		<ul id="posted_subject" class="<?php if($conversation['conversation_unread']){ echo 'unread'; } ?>">
		
			<li class="time_message">
				<small><?php echo date('y/m/d H:i:s', $conversation['conversation_last_reply']) ?></small>
			</li>
			<li>
				<a href="index.php?page=view_conversation&amp;conversation_id=<?php echo $conversation['conversation_id'] ?>"><?php echo mb_strimwidth($conversation['conversation_subject'], 0, 56, "...", 'UTF-8'); ?></a>
				<a href="index.php?page=inbox&amp;delete_conversation=<?php echo $conversation['conversation_id'] ?>">&nbsp;<i class="fas fa-trash-alt"></i></a>
			</li>
			
		</ul>
<?php } ?>
	</div>
</section>
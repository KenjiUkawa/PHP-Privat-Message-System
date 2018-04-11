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
	$errors[]="No Messages";
}
if(!empty($errors)){
	foreach($errors as $error){
		echo $error;
	}
}

		
	//load header
	include($include_header);
		
?>

<a href="index.php?page=new_conversation">New Message</a>


<?php foreach($conversations as $conversation){ ?>

	<style type="text/css">
		.unread{font-weight: bold;}
	</style>

	<div class="<?php if($conversation['conversation_unread']){ echo 'unread'; } ?>">
	
		<p>
			<a href="index.php?page=inbox&amp;delete_conversation=<?php echo $conversation['conversation_id'] ?>">[x]</a>
			<a href="index.php?page=view_conversation&amp;conversation_id=<?php echo $conversation['conversation_id'] ?>"><?php echo $conversation['conversation_subject'] ?></a>
		</p>
		<p><small>Last Reply: <?php echo date('y/m/d H:i:s', $conversation['conversation_last_reply']) ?></small></p>
		
	</div>

<?php } ?>
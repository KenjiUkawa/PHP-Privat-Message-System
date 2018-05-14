<?php

//ini_set('display_errors', 1);


/*-----------------------------------------------
	conversation summery
------------------------------------------------*/
function fetch_conversation_summery($mysqli){
	//DB plan: https://manablog.org/wp-content/uploads/2017/01/pm_php_db.jpg
	$sql="SELECT
				conversations.conversation_id,
				conversations.conversation_subject,
				MAX(conversations_messages.message_date) AS conversation_last_reply,
				MAX(conversations_messages.message_date) > conversations_members.conversation_last_view AS conversation_unread
			FROM
				conversations
			-- convine conversations.conversation_id and conversations_messages.conversation_id
			-- basically display all data on the left table(conversations) of JOIN
			LEFT JOIN
				conversations_messages
			ON
				conversations.conversation_id=conversations_messages.conversation_id

			-- conbine conversations.conversation_id and conversations_members.conversation_id
			INNER JOIN
				conversations_members
			ON
				conversations.conversation_id=conversations_members.conversation_id

			WHERE
				conversations_members.user_id={$_SESSION['user_id']}
			AND
				conversations_members.conversation_deleted=0

			-- specify columns which are group up
			GROUP BY
				conversations.conversation_id

			ORDER BY
				conversation_last_reply DESC;";

	$result=$mysqli->query($sql);
	$conversations=array();

	while($row=$result->fetch_assoc()){
		$conversations[]=$row;
	}
	return $conversations;

}


function create_conversation($user_ids,$subject,$body,$mysqli){
	$subject=$mysqli->real_escape_string(htmlentities($subject));
	$body=$mysqli->real_escape_string(htmlentities($body));

	//insert the info into the conversations table
	$sql="INSERT INTO conversations (conversation_subject) VALUES ('$subject')";
	$result=$mysqli->query($sql);

	//get the ID of last insert into conversations table
	$conversation_id=mysqli_insert_id($mysqli);

	$sql="INSERT INTO
				conversations_messages(
					conversation_id,
					user_id,
					message_date,
					message_text
				)
				VALUES(
					$conversation_id,
					{$_SESSION['user_id']},
					UNIX_TIMESTAMP(),
					'$body')";
	$result=$mysqli->query($sql);

	//insert info into conversations_members table
	//proccessing for multi-users below
	$values=array();
	$user_ids[]=$_SESSION['user_id'];
	$time=time();

	foreach($user_ids as $user_id){
		$user_id=(int)$user_id;
		$values[]="($conversation_id,$user_id,$time,0)";
	}

	$sql="INSERT INTO
				conversations_members(
					conversation_id,
					user_id,
					conversation_last_view,
					conversation_deleted)
			VALUES".implode(", ",$values);
	$result=$mysqli->query($sql);
}

/*-----------------------------------------------
	validation for conversarion ID
------------------------------------------------*/
function validate_conversation_id($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;
	$sql="SELECT
			COUNT(1)
			FROM
				conversations_members
			WHERE
				conversation_id={$conversation_id}
			AND
				user_id={$_SESSION['user_id']}
			AND
				conversation_deleted=0";
	$result=$mysqli->query($sql);
	if($result->num_rows===1){
		return true;
	}
}


/*-----------------------------------------------
	delete conversation
------------------------------------------------*/
function delete_conversation($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;

	//choose conversation_delated (DISTINCT is for group messages)
	$sql="SELECT DISTINCT
				conversation_deleted
			FROM
				conversations_members
			WHERE
				conversation_id={$conversation_id}
			AND
				user_id!={$_SESSION['user_id']}";
	$result=$mysqli->query($sql);

	//to get a flag (1 or 0 ) of conversation_deleted
	while($row=mysqli_fetch_assoc($result)){
		$conversation_deleted=$row['conversation_deleted'];
	}

	if($result->num_rows===1&&$conversation_deleted==1){
		//delete all messages completely
		$sql01="DELETE FROM conversations WHERE conversation_id={$conversation_id}";
		$sql02="DELETE FROM conversations_members WHERE conversation_id={$conversation_id}";
		$sql03="DELETE FROM conversations_messages WHERE conversation_id={$conversation_id}";

		$mysqli->query($sql01);
		$mysqli->query($sql02);
		$mysqli->query($sql03);
	}else{
		//only update the flag of conversation_deteled (remein the data)
		$sql="UPDATE
					conversations_members
				SET
					conversation_deleted=1
				WHERE
					conversation_id={$conversation_id}
				AND
					user_id={$_SESSION['user_id']}";
		$mysqli->query($sql);
	}
}

/*-----------------------------------------------
	get subject for view_conversation
------------------------------------------------*/
function get_current_subject($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;

	$sql="SELECT
				conversation_subject
			FROM
				conversations
			WHERE
				conversation_id={$conversation_id}";

	$result=$mysqli->query($sql);

	foreach( $result as $value ){
		$subject=$value['conversation_subject'];
	}
	return $subject;
}

/*-----------------------------------------------
	fetch_conversation_messages
------------------------------------------------*/
function fetch_conversation_messages($conversation_id, $mysqli){
	$conversation_id=(int)$conversation_id;

	$sql="SELECT
				conversations_messages.message_date,
				conversations_messages.message_date > conversations_members.conversation_last_view AS message_unread,
				conversations_messages.message_text,
				conversations_messages.user_id,
				users.user_name,
				users.user_icon,
				conversations.conversation_subject
			FROM
				conversations_messages
			INNER JOIN
				users
			ON
				conversations_messages.user_id=users.user_id
			INNER JOIN
				conversations_members
			ON
				conversations_messages.conversation_id=conversations_members.conversation_id
			INNER JOIN
				conversations
			ON
				conversations_messages.conversation_id=conversations.conversation_id
			WHERE
				conversations_messages.conversation_id={$conversation_id}
				-- date will be repeted if delete below
				-- AND conversations_members.user_id={$_SESSION['user_id']}
			-- specify columns which are group up
			GROUP BY
				conversations_messages.message_date
			ORDER BY
				conversations_messages.message_date DESC";
	$result=$mysqli->query($sql);
	$messages=array();

	while($row = $result->fetch_assoc()) {
		$messages[] = $row;
	}
	return $messages;
}


/*-----------------------------------------------
	update the date of message when readed
------------------------------------------------*/
function update_conversation_last_view($conversation_id, $mysqli){
	$conversation_id=(int)$conversation_id;

	$sql="UPDATE
				conversations_members
			SET
				conversation_last_view=UNIX_TIMESTAMP()
			WHERE
				conversation_id={$conversation_id}
			AND
				user_id={$_SESSION['user_id']}";
	$mysqli->query($sql);
}



/*-----------------------------------------------
	add conversation message
------------------------------------------------*/
function add_conversation_message($conversation_id,$text,$mysqli){
	$conversation_id=(int)$conversation_id;
	$text=$mysqli->real_escape_string(htmlentities($text));

	$sql="INSERT INTO
				conversations_messages(
					conversation_id,
					user_id,
					message_date,
					message_text
				)
			VALUES(
				{$conversation_id},
				{$_SESSION['user_id']},
				UNIX_TIMESTAMP(),
				'{$text}'
			)";

	$result=$mysqli->query($sql);

}


?>
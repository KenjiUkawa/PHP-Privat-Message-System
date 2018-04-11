<?php

function create_conversation($user_ids, $subject, $body, $mysqli){
	$subject=$mysqli->real_escape_string(htmlentities($subject));
	$body=$mysqli->real_espacpe_string(htmlentities($body));
	
	//insert infomations into the conversation table
	$sql="INSERT INTO conversations (conversation_subject) VALUES ('$subject')";
	$result=$mysqli->query($sql);
	
	//get the last inserted ID from coversation table
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
				'$body'
			)";
	$result=$mysqli->query($sql);
	
	//insert infomation into conversation_members table
	//process below is for multi-users
	$values=array();
	$user_ids[]=$_SESSION['user_id'];
	$time=time();
	
	foreach($user_ids as $user_id){
		$user_id=(int) $user_id;
		$values[]= "($conversation_id, $user_id, $time, 0)";
	}
	
	$sql="INSERT INTO
				conversations_members(
					conversation_id,
					user_id,
					conversation_last_view,
					conversation_deleted
				)
			VALUES " . implode(", ", $values);
	
	$result=$mysqli->query($sql);
	
}

/*------------------------------------------------------
	inbox page
------------------------------------------------------*/
function fetch_conversation_summery($mysqli){
	//NOTE) diagram of DB : https://manablog.org/wp-content/uploads/2017/01/pm_php_db.jpg
	$sql="SELECT
				conversations.conversation_id,
				conversations.conversation_subject,
				MAX(conversations_message.message_date) AS conversation_last_reply,
				-- bold unread messages
				MAX(conversations_messages.message_date) > conversations_members.conversation_last_view AS conversation_unread
			
			FROM
				conversations
				-- joint conversations.conversation_id & conversations_messages.conversation_id
				-- the data in the left side table(conversations) of JOIN displays all
			LEFT JOIN conversations_messages
			ON
				conversations.conversation_id=conversations_messages.conversation_id
				
				-- joint conversations.conversation_id & conversations_members.conversation_id
				-- the data required by both sides of table displays only
			INNER JOIN
				conversations_members
			ON
				conversations.conversation_id=conversations_members.conversation_id
				
			WHERE
				conversations_members.user_id={$_SESSION['user_id']}
			AND
				conversations_members.conversation_daleted=0
				
				-- specify target clumes going to be gruped
			GROUP BY
				conversations.conversation_id
				
			ORDER BY
				conversation_last_reply DESC;";
	
	$result=$mysqli->query($sql);
	$conversations=array();
	
	while($row=$result->getch_assoc()){
		$conversations[]=$row;
	}
	return $conversations;
				
}

/*-----------------------------------------------------
	validate_canversation_id
-----------------------------------------------------*/
function validate_canversation_id($conversation_id, $mysqli){
	$conversation_id=(int)$conversation_id;
	$sql="SELECT COUNT(1)
			FROM
				conversation_members
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

/*-----------------------------------------------------
	delete_conversation
-----------------------------------------------------*/
function delete_conversation($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;
	
	//select conversation_deleted (DISTINCT is for groupe messages)
	$sql="SELECT DISTINCT conversation_deleted
			FROM
				conversations_members
			WHERE
				conversation_id={$conversation_id}
			AND
				user_id !={$_SESSION['user_id']}";
	
	$result=$mysqli->query($sql);
	
	//get a flag(0 or 1) of conversation_deleted
	while($row=mysqli_fetch_assoc($result)){
		$conversation_deleted=$row['conversation_deleted'];
	}
	
	if($result->num_rows===1&&$conversation_deleted==1){
		//completely delete all messages
		$sql01="DELETE FROM conversations WHERE conversation_id={$conversation_id}";
		$sql02="DELETE FROM conversations_members WHERE conversation_id={$conversation_id}";
		$sql03="DELETE FROM conversations_messages WHERE conversation_id={$conversation_id}";
		
		$mysqli->query($sql01);
		$mysqli->query($sql02);
		$mysqli->query($sql03);
	}else{
		//only update a flag of conversation+deleted (the data is remained)
		$sql="UPDATE
					conversation_members
				SET
					conversation_delete=1
				WHERE
					conversation_id={$conversation_id}
				AND
					user_id={$_SESSION['user_id']}";
		$mysqli->query($sql);
	}
}

/*-----------------------------------------------------
	get conversation messages
-----------------------------------------------------*/
function fetch_conversation_messages($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;
	
	$sql="SELECT
				conversation_messages.message_date,
				conversation_messages.message_date > conversation_members.conversation_last_view AS message_unread,
				conversation_messages.message_text,
				user.user_name
			FROM
				conversations_messages
			INNER JOIN
				users
			ON
				conversation_messages.user_id=user.user_id
			INNER JOIN
				conversation_members
			ON
				conversations_messages.conversation_id=conversations_members.conversation_id
				
			WHERE
				conversation_messages.conversation_id={$conversation_id}
			-- deta will be duplicated if delete below (to understand, check result of query)
			AND
				conversation_members.user_id={$_SESSION['user_id']}
			ORDER BY
				conversation_messages.message_date
			DESC";
	
	$result=$mysqli->query($sql);
	$messages[]=array();
	
	while($row=$result->fetch_assoc()){
		$messages[]=$row;
	}
	return $messages;
}

/*-----------------------------------------------------
	update the time when being read
-----------------------------------------------------*/
function update_conversation_last_view($conversation_id,$mysqli){
	$conversation_id=(int)$conversation_id;
	$sql="UPDATE
				conversation_members
			SET
				conversation_last_view=UNIX_TIMESTAMP()
			WHERE
				conversation_id={$conversation_id}
			AND
				user_id={$_SESSION['$user_id']}";
	$mysqli->query($sql);
}

	
?>
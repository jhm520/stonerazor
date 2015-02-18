<?php
	require "scripts/header.php";
	
	function post_topic ($mysqli){
		$topic_subject = $_POST["topic_subject"];
		$topic_post_body = $_POST["topic_body"];
		
		$date = date('Y-m-d H:i:s');
		
		$prep_query = 
			"INSERT INTO 
				topic (user_id, topic_timestamp,
				topic_update, topic_subject, forum_id, topic_ip)
			VALUES 
				(" . $_SESSION["user_id"] . ", '" . $date . "', '"
				. $date . "', '" . $topic_subject . "', " . 1
				. ", '" . $_SERVER['REMOTE_ADDR'] . "');"; 

		$post_topic= $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		$topic_id = mysqli_insert_id($mysqli);
		
		$prep_query = 
			"INSERT INTO
				post (user_id, post_timestamp, post_update, 
				post_body, topic_id, post_ip)
			VALUES 
				(" . $_SESSION["user_id"] . ", '" . $date 
				. "', '" . $date . "', '" . $topic_post_body . "', "
				. $topic_id . ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$post_topic_post = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
	}

	if ($_POST["topic_submit"]){
		post_topic($mysqli);
		header('Location:forum.php');
	}

?>
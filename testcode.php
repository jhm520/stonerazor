<?php
	require "scripts/header.php";
	
	$prep_query = "SELECT topic.topic_id, topic.user_id, topic.topic_timestamp, topic.post_id, topic.topic_update, topic.topic_subject, topic.forum_id, readtopic.user_id, readtopic.topic_id FROM topic LEFT JOIN readtopic ON topic.user_id=readtopic.user_id";
	
	$result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
	
	if ($result){
		while ($row = $result->fetch_array()){
			echo $row["user_id"];
			echo $row["topic_id"];
		}
	
	}
	

?>
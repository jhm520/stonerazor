<?php
	require_once "pagiation.php";
	
	function post_topic ($mysqli, $forum_id) {
	
		//$topic_subject = $mysqli->real_escape_string(strip_tags($_POST["topic_subject"]));
		//$topic_post_body = $mysqli->real_escape_string(strip_tags(nl2br($_POST["topic_body"]), "<br>"));
		
		// Changed this stuff. See post in development forum. Left topic_subject unchanged.
		
		$topic_subject = $mysqli->real_escape_string(htmlentities($_POST["topic_subject"]));
		$topic_post_body = $mysqli->real_escape_string($_POST["topic_body"]);
		
		/*Get current date*/
		$date = date('Y-m-d H:i:s');
		
		$prep_query = 
			"INSERT INTO 
				topic (user_id, topic_timestamp,
				topic_update, topic_subject, forum_id, topic_ip)
			VALUES 
				(" . $_SESSION["user_id"] . ", '" . $date . "', '"
				. $date . "', '" . $topic_subject . "', " . $forum_id
				. ", '" . $_SERVER['REMOTE_ADDR'] . "');"; 

		$post_topic= $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		$topic_id = mysqli_insert_id($mysqli); // should there be a failsafe here? If this isn't an integer, the next query will fuck up.
		
		$prep_query = 
			"INSERT INTO
				post (user_id, post_timestamp, post_update, 
				post_body, topic_id, post_ip)
			VALUES 
				(" . $_SESSION["user_id"] . ", '" . $date 
				. "', '" . $date . "', '" . $topic_post_body . "', "
				. $topic_id . ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$post_topic_post = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		if ($post_topic_post){
			header('Location: topic.php?topic_id='.$topic_id);
		}
	}
	
	/*Print formatted HTML for each topic*/
	function print_topics ($mysqli, $forum_id)
	{
		global $is_logged_in;
		$topics_per_page = 15;
		
		$topic_query = "SELECT topic_id FROM topic WHERE forum_id=".$forum_id;
		
		/*Query database for all topics*/
		$all_topics = $mysqli->query($topic_query) or die("Error:" . $mysqli->error);
		
		/*Get total number of topics*/
		$num_all_topics = mysqli_num_rows($all_topics);
		
		/*Get number of pages from the number of topics*/
		$num_pages = ceil($num_all_topics/$topics_per_page);
		
		/*Get the index of the first topic to be displayed*/
		$start = 0;
		$start += strint($_GET["start"]);
		
		/* Get all topics and the user that posted them*/
		$prep_query = "
			SELECT 
				topic.topic_id, topic.user_id, 
				topic.topic_timestamp, topic.post_id, 
				topic.topic_update, topic.topic_subject, 
				topic.forum_id, user.user_name 
			FROM 
				topic LEFT JOIN user 
				ON 
				user.user_id=topic.user_id 
			WHERE 
				topic.forum_id=".$forum_id." 
			ORDER BY 
				topic.topic_update DESC
			LIMIT "
				. $start . ", " . $topics_per_page;
		
		$topics = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		if ($topics)
		{
			/*Get number of topics*/
			$num_topics = mysqli_num_rows($topics);
			
			/*Get number of pages from the number of topics*/
			$num_pages = ceil($num_all_topics/$topics_per_page);
			
			/*Print pagiation*/
			
			pagiation($start, $topics_per_page, $num_all_topics);
			
			echo '<div id="topiclist" class="page '.($page_count==$page_num ? 'selected"' : '"').'>
						<ul>';
			/*Iterate through topics*/
			while ($row = $topics->fetch_array())
			{	
				/*Get all replies to this topic, with the user that posted it, order by timestamp*/
				$prep_query = "
					SELECT 
						post.post_id, post.post_timestamp, 
						post.post_update, user.user_id, user.user_name 
					FROM 
						post LEFT JOIN topic 
					ON 
						topic.topic_id=post.topic_id 
					LEFT JOIN 
						user 
					ON 
						topic.user_id=user.user_id 
					WHERE 
						topic.topic_id=".$row["topic_id"]." 
					ORDER BY 
						post.post_timestamp DESC";
				
				$post_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
				
				/*Get number of posts in this topic*/
				$numposts = mysqli_num_rows($post_result);
				
				/*Take the latest post (posts are sorted by timestamp) off the top of the list*/
				$lastpost = $post_result->fetch_array();
				
				/*if user is not set, all topics are unread*/
				$topic_read = "unread";
				
				//$logged_in = login_check($mysqli);
				
				/*if someone is logged in*/
				if ($is_logged_in)
				{
					/*Get all that user's readtopics*/
					$prep_query = 'SELECT topic_id FROM readtopic WHERE user_id='.$_SESSION['user_id'];
					
					$readtopic_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
					
					/*If this topic id is in readtopic, the user has read this topic*/
					while ($readtopic = $readtopic_result->fetch_array())
					{
						if ($readtopic["topic_id"] == $row["topic_id"])
						{
							$topic_read = "read";
							break;
						}
					}
				}
				
				$delete = '';
				
				// parse the date and time
				
				$current_datetime = new DateTime();
				$post_datetime = new DateTime($lastpost["post_timestamp"]);
				$topic_datetime = new DateTime($row["topic_timestamp"]);
				$post_datetime_diff = $current_datetime->diff($post_datetime);
				$topic_datetime_diff = $current_datetime->diff($topic_datetime);
				$post_datetime_array = array($post_datetime_diff->format("%s"),$post_datetime_diff->format("%i"),$post_datetime_diff->format("%h"),$post_datetime_diff->format("%d"),$post_datetime_diff->format("%m"),$post_datetime_diff->format("%y"));
				$topic_datetime_array = array($topic_datetime_diff->format("%s"),$topic_datetime_diff->format("%i"),$topic_datetime_diff->format("%h"),$topic_datetime_diff->format("%d"),$topic_datetime_diff->format("%m"),$topic_datetime_diff->format("%y"));
				$post_datetime_text = "";
				$topic_datetime_text = "";
				$datetype_type_cycle = array("seconds","minutes","hours","days","months","years");
				foreach ($post_datetime_array as $key => $val)
				  {
				  if ((int)$val>0) $post_datetime_text = $val . " " . $datetype_type_cycle[$key] . " ago";
				  }
				foreach ($topic_datetime_array as $key => $val)
				  {
				  if ((int)$val>0) $topic_datetime_text = $val . " " . $datetype_type_cycle[$key] . " ago";
				  }
				
				
				/*print the HTML*/
				echo '<li class="'.$topic_read.'">
					<ul>
					  <li class="title">'.$numposts.' replies</li>
						<li class="reply">Last reply by <a href="user.php?user_id='.$lastpost["user_id"].'">'.$lastpost["user_name"].'</a> ' . $post_datetime_text . '</li>
						<li class="author">Posted by <a href="user.php?user_id='.$row["user_id"].'">'.$row["user_name"].'</a> ' . $topic_datetime_text . '</li>'
						. $delete . '
					</ul><a href="topic.php?topic_id='.$row["topic_id"].'">'.$row["topic_subject"].'</a></li>';
				$topic_count++;
			}
			echo '</ul>
						</div>';
			/*Print pagiation*/
			pagiation($_GET["start"], $topics_per_page, $num_all_topics);
		}
	}
	
	function read_all_topics ($mysqli, $forum_id) {
		/*Get all topic ids*/
		$topic_query = 
			"SELECT
				topic_id
			FROM
				topic
			WHERE
				forum_id=".$forum_id;
				
		echo $topic_query;

		$topic_result = $mysqli->query($topic_query) or die("Error:" . $mysqli->error);
		
		while ($topic = $topic_result->fetch_array()){
			/*Get all topics this user has already read*/
			$readtopic_query = 
				"SELECT 
					user_id, topic_id
				FROM
					readtopic
				WHERE
					topic_id=" . $topic["topic_id"] 
					. " AND user_id=" . $_SESSION["user_id"];
			
			$readtopic_result = $mysqli->query($readtopic_query) or die("Error:" . $mysqli->error);
			
			/*If the user has read this topic, store that truth value*/
			if ($readtopic = $readtopic_result->fetch_array()){
				$read_topic = True;
			}else{
				$read_topic = False;
			}
			
			/*If the user hasn't read the topic*/
			if ($read_topic == False){
				/*Mark the topic as read, insert new readtopic*/
				$prep_query = 
					"INSERT INTO
						readtopic (user_id, topic_id) 
					VALUES
						(" . $_SESSION["user_id"] . ", " .  $topic["topic_id"] . ");";
				
				$insert_readtopic_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
				
				if ($insert_readtopic_result){
					header('Location:'.$_SERVER['PHP_SELF']);
				}
			}
		}
	}
	
	function delete_topic ($mysqli, $topic_id){
		$prep_query = "SELECT topic_id, user_id FROM topic WHERE topic_id=".$topic_id;
		$topic_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		$user_owned = False;
		if ($row = $topic_result->fetch_array()){
			$user_owned = ($row["user_id"] == $_SESSION["user_id"]);
			
			if ($user_owned){
				$prep_query = "DELETE FROM topic WHERE topic_id=".$topic_id;
				
				$delete_topic_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
				
				if ($delete_topic_result){
					$prep_query = "DELETE FROM readtopic WHERE topic_id=".$topic_id;
					
					$delete_readtopic_result = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
					
					header('Location:'.$_SERVER['PHP_SELF']);
				}
			}
		}
		
		
		
	}
?>
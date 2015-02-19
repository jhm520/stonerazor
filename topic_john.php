<?php
	$additional_stylesheets = array("topic.css");
	$additional_scripts = array("sha512.js","forms.js","jquery-1.11.1.min.js","topic.js", "jquery.autosize.js", "jquery.autosize.min.js", "alert.js");
	require "scripts/header.php";
	require "scripts/forum_script.php";
	
	function post_post($mysqli, $topic_id)
	{
		/*Get post body*/
		//$post_body = $mysqli->real_escape_string(strip_tags(nl2br($_POST["post_body"]), '<br>'));
		
		// I altered this. We should not use strip tags because it deletes characters like <. We want
		// those to be replaced with the appropriate ascii when selecting the data. Also removed
		// the nl2br because I think it's better to store the data raw. 
		$post_body = $mysqli->real_escape_string($_POST["post_body"]);
		
		/*Get current date*/
		$date = date('Y-m-d H:i:s');
		
		/*Insert new post*/
		$prep_query = 
			"INSERT INTO
				post (user_id, post_timestamp, post_update, 
				post_body, topic_id, post_ip)
			VALUES 
				(" . $_SESSION["user_id"] . ", '" . $date 
				. "', '" . $date . "', '" . $post_body . "', "
				. $topic_id . ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$post_post = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		$prep_query = "UPDATE topic SET topic_update='".$date."' WHERE topic_id=".$topic_id;
		
		$update_topic = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		/*Delete all the readtopics from this topic, since this topic is being updated with a new post*/
		$delete_readtopic = "
			DELETE FROM
				readtopic
			WHERE
				topic_id=".$topic_id;
		
		$delete_readtopic_result = $mysqli->query($delete_readtopic) or die("Error:" . $mysqli->error);
		
		if ($post_post && $update_topic){
			header('Location:'.$_SERVER['PHP_SELF'].'?topic_id='.$topic_id);
		}
	}
	
	function edit_post($mysqli, $post_id){
		//$edit_body = $mysqli->real_escape_string(strip_tags(nl2br($_POST["edit_body"]), '<br>'));
		$edit_body = $mysqli->real_escape_string($_POST["edit_body"]);
		
		$update_query = "UPDATE post SET post_body='".$edit_body."' WHERE post_id=".$post_id." LIMIT 1;";
		
		$update_post = $mysqli->query($update_query) or die("Error:" . $mysqli->error);
	}
	
	function delete_post($mysqli, $post_id){
	  $delete_body = "This post has been deleted.";
	  
	  $update_query = "UPDATE post SET post_body='".$delete_body."', post_delete=1 WHERE post_id=".$post_id." LIMIT 1;";
	  
	  $update_post = $mysqli->query($update_query) or die("Error:". $mysqli->error);
	}
	
	$topic_id = strint($_GET["topic_id"]);
	
	if ($is_logged_in)
	{
		if ($_POST["post_submit"])
		{
			post_post($mysqli, $topic_id);
		}
		else if ($_POST["edit_submit"])
		{
			edit_post($mysqli, $_POST["edit_post"]);
		}
		else if ($_GET["delete_post"])
		{
		  echo delete_post($mysqli, $_GET["delete_post"]);
		}
		
		$readtopic_query = "
			SELECT 
				* 
			FROM 
				readtopic 
			WHERE user_id=".$_SESSION["user_id"]." AND topic_id=".$topic_id."
			LIMIT 1";
		
		$readtopic_result = $mysqli->query($readtopic_query) or die("Error:" . $mysqli->error);
		
		if (!($readtopic = $readtopic_result->fetch_array()))
		{
			$insert_readtopic = "
				INSERT INTO 
					readtopic (user_id, topic_id)
				VALUES ("
					. $_SESSION["user_id"] . ", " . $topic_id . ")";
			
			$insert_readtopic_result = $mysqli->query($insert_readtopic) or die("Error:" . $mysqli->error);
		}
	}
	
	if ($topic_id)
	{
		//Get the topic to be displayed
		$topic_query = "SELECT 
							topic_id, topic_subject, topic_timestamp, forum_id
						FROM
							topic
						WHERE
							topic_id=".$topic_id;
		
		$topic_result = $mysqli->query($topic_query) or die("Error:" . $mysqli->error);
		
		/*
		Make sure there's only one topic with this id
		*/
		if (mysqli_num_rows($topic_result) == 1)
		{
			$topic = $topic_result->fetch_array();
			
			$forum_query = "
				SELECT
					forum_id, forum_title, forum_read, forum_write, forum_moderate
				FROM
					forum
				WHERE
					forum_id=".$topic["forum_id"]."
				LIMIT
					1";
			
			$forum_result = $mysqli->query($forum_query) or die("Error:". $mysqli->error);
			
			if (mysqli_num_rows($forum_result) == 1)
			{
				$forum = $forum_result->fetch_array();
			}
		}
	}
	else
	{
		header("Location: http://stonerazor.com/");
	}
	
	require "scripts/html_header.php";
	?>
	
	<div id="deletebox">
		<p>Are you sure you want to delete this post?</p>
		<br>
		<a id="deletebox_yes" href="">Delete</a>
	</div>
	<div id="container">
			<div class="titlebar">
				<ul class="nav">
					<li><a href="topic.php?topic_id=<?php echo $topic["topic_id"];?>"> <?php echo $topic["topic_subject"]; ?></a></li>
					<li><a href="forum.php?forum_id=<?php echo $topic["forum_id"]; ?>"><?php echo $forum["forum_title"]; ?></a></li>
					<li><a href="index.php">Forums</a></li>
				</ul>
				<ul class="navicons">
				<?php
if ($is_logged_in) // should check permissions in the future.
	{
?>
				<li><a id="new_topic_btn" href="#" onmouseover="titletext('Create post','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M387.628,238.223L202.581,423.267L49.945,461.139L90.641,311.01l184.887-184.888l28.352,28.351
L126.967,331.391l-10.901,45.092l20.288,20.287l46.166-10.148l176.755-176.752L387.628,238.223z M405.176,220.676l56.77-56.773
L349.845,51.805l-56.77,56.771L405.176,220.676z M321.35,171.896L146.656,346.587l20.506,20.506l174.693-174.691L321.35,171.896z"/> </svg>

				</a></li>
<?php
	}
?>
				<li><a href="javascript:location.reload();" onmouseover="titletext('Refresh','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M373.223,142.573l-37.252,37.253c-20.225-20.224-48.162-32.731-79.021-32.731
c-61.719,0-111.752,50.056-111.752,111.776c0,0.016,0-0.016,0,0h43.412l-69.342,69.315L50,258.871h42.514c0-0.008,0,0.006,0,0
c0-90.816,73.621-164.46,164.436-164.46C302.357,94.411,343.467,112.816,373.223,142.573z M462,253.129l-69.268-69.316
l-69.342,69.316h43.412c0,0.016,0-0.017,0,0c0,61.72-50.033,111.776-111.752,111.776c-30.859,0-58.797-12.508-79.021-32.731
l-37.252,37.253c29.758,29.757,70.867,48.162,116.273,48.162c90.814,0,164.436-73.644,164.436-164.459c0-0.007,0,0.008,0,0H462z"/> </svg>

				</a></li>
				</ul>
				<span class="titletext" id="titletext_1">&nbsp;</span>
			</div>
			<div class="titlefade">&nbsp;</div>
			
	<?php
		if ($is_logged_in)
		{
			echo 
				'<div id="new_topic" style="display:none">
					<form action="topic.php?topic_id='.$topic_id.'&start='.$start.'" method="post" id="new_topic_form">
						Body: <textarea cols="40" rows="5" name="post_body" class="post_textarea"></textarea><br>
						<!--<a href="new_topic.php?forum_id=1" id="new_topic_post">Post</a>-->
						<input type="submit" name="post_submit" value="Post"/>
					</form>
				</div>';
		}
	?>
		
	<?php

	/*Print all posts in this topic*/
	if ($topic){
	
		$all_post_query = "SELECT post_id FROM post WHERE topic_id=".$topic_id."";
		
		$all_post = $mysqli->query($all_post_query) or die("Error:" . $mysqli->error);
		
		$num_all_post = mysqli_num_rows($all_post);
	
		$post_per_page = 5;
		$page_per_load = 10;
		$post_per_load = $post_per_page*$page_per_load;
		
		?>
		<script>
			post_per_page = "<?php echo $post_per_page; ?>";
			page_per_load = "<?php echo $page_per_load; ?>";
			post_per_load = post_per_page*page_per_load;
			
			var query = location.href.split('#');
			document.cookie = query[1];
			
			
		</script>
		<?php
		
		$start = 0;
		$start = strint($_GET["start"]);
		
		//$start = strint($_COOKIE['start']);
		
		$url = parse_url(getlink(array(), array(), array()), PHP_URL_FRAGMENT);
		echo $url;
		
		if ($start >= $num_all_post-1)
		{
			$start = $num_all_post-1;
		}
		
		
		$loadstart = 0;
		
		if ($start > 0)
		{
			$loadstart = $start-($post_per_load/2);
			
			if ($loadstart < 0)
			{
				$loadstart = 0;
			}
			
			$loadend = $loadstart+$post_per_load;
		}
				
		/*
			Get info from the post table joined with the user table
		*/
		$post_query = "
			SELECT 
				post.post_id, post.post_timestamp, post.post_update, 
				post.post_body, post.post_ip, post.post_delete, user.user_id,
				user.user_name, user.user_email, user.user_timestamp 
			FROM
				post LEFT JOIN user
			ON
				post.user_id=user.user_id
			WHERE
				post.topic_id=". $topic_id . "
			ORDER BY
				post.post_timestamp ASC
			LIMIT "
				. $loadstart . ", " . $post_per_load;
				
		$post_result = $mysqli->query($post_query) or die("Error:" . $mysqli->error);
		
		pagiation($start, $post_per_page, $num_all_post, $loadstart, $page_per_load, True);
		?>
		
		<div id="postlist">
			<ul>
		<?php
		$post_count = 0;
		while ($post = $post_result->fetch_array())
		{
			if ($post_count+$loadstart < $start)
			{
				$noshow = "noshow";
			}
			else if ($post_count+$loadstart >= $start+$post_per_page)
			{
				$noshow = "noshow";
			}
			else
			{
				$noshow = "";
			}
			
			$edit_btn='';
			$delete_btn='';
			if ($post["user_id"] == $userdata["user_id"]){
			  if (!$post["post_delete"]){
				  $edit_btn = '<a href="javascript: void();" name="'.$post["post_id"].'" class="edit_btn">Edit</a>';
				  $delete_btn = '<a href="javascript: alert_open(\'deletebox\',\''.getlink(array(), array("delete_post"), array($post["post_id"])).'\');" id="deletebtn'.$post["post_id"].'" name="'.$post["post_id"].'" class="delete_btn">Delete</a>';
				  //$delete_btn = '<a href="javascript: alert(\''.$delete_alert.'\');">Delete</a>';
			  }
			}
		?>
				<li name="<?php echo $loadstart+$post_count;?>" class="post <?php echo $noshow;?>">
					<div class="post_user">
						<ul>
							<li class="avatar"></li>
							<li class="username">Username: <a href="user.php?user_id=<?php echo $post["user_id"]; ?>"><?php echo $post["user_name"]; ?></a></li>
							<li class="timestamp">Posted at: <?php echo $post["post_timestamp"]; ?></li>
							<li class="buttons"><?php echo $edit_btn." " .$delete_btn;?></li>
						</ul>
					</div>
					<div class="post_body" name="<?php echo $post["post_id"]; ?>">
						<div class="text_body" name="<?php echo $post["post_id"]; ?>"><?php echo nl2br(htmlentities($post["post_body"]),false); ?></div>
						<?php 
							if ($post["user_id"] == $userdata["user_id"])
							{
						?>
						<form action="topic.php?topic_id=<?php echo $topic_id; ?>&start=<?php echo $start; ?>" method="post" class="edit_body" name="<?php echo $post["post_id"]; ?>" style="display:none">
							<textarea name="edit_body" class="edit_textarea"><?php echo str_replace('<br />', '', $post["post_body"]); ?></textarea>
							<input type="hidden" name="edit_post" value="<?php echo $post["post_id"]; ?>"/>
							<input type="submit" name="edit_submit" value="Submit" />
						</form>
						<?php
							}
						?>
					</div>
				</li>
		<?php
			$post_count++;
		}
		?>
		<!--
		<li>
		  <div class="post_user">
				<ul>
					<li class="avatar"></li>
					<li class="username">Username: <a href="user.php?user_id=<?php echo $userdata["user_id"]; ?>"><?php echo $userdata["user_name"]; ?></a></li>
					<li class="timestamp">Posted at: <?php echo "current time"; ?></li>
				</ul>
			</div>
			<div class="post_body">
				<form action="<?php echo getlink(array(), array(), array());?>" method="post" class="edit_body">
					<textarea name="edit_body" class="edit_textarea" placeholder="Enter your message here..."></textarea>
					<input type="submit" name="post_submit" value="Post" />
				</form>
			</div>
		  <div id="new_topic" style="display:none">
				<form action="topic.php?topic_id='.$topic_id.'&start='.$start.'" method="post" id="new_topic_form">
					Body: <textarea cols="40" rows="5" name="post_body" class="post_textarea"></textarea><br>
					<input type="submit" name="post_submit" value="Post"/>
				</form>
			</div>
		</li>
		-->
			</ul>
		</div>
			<?php
	}
?>
</div>


<?php
	require "scripts/html_footer.php";
	require "scripts/footer.php";
?>
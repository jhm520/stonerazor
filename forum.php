<?php
	$additional_stylesheets = array("forum.css");
	$additional_scripts = array("sha512.js","forms.js","jquery-1.11.1.min.js","forum.js", "jquery.autosize.js", "jquery.autosize.min.js");
	$newtopicbox_on = true;
	require "scripts/header.php";
	require "scripts/forum_script.php";
	
	$forum_id = strint($_GET["forum_id"]); // must come before html_header
	
	if ($forum_id){
		$forum_query = "
			SELECT 
				forum_id, forum_title, 
				forum_read, forum_write, 
				forum_moderate 
			FROM 
				forum 
			WHERE 
				forum_id=".$forum_id." 
			LIMIT 
				1";
		$forum_result = $mysqli->query($forum_query) or die("Error:" . $mysqli->error);
		$forum = $forum_result->fetch_array();
	}
	else
	{
		header("Location: http://stonerazor.com/");
	}
	
	if ($is_logged_in)
	{
		$user_read = $userdata["user_permission"] <= $forum["forum_read"];
		$user_write = $userdata["user_permission"] <= $forum["forum_write"];
		$user_moderate = $userdata["user_permission"] <= $forum["forum_moderate"];
		
		if ($_POST["topic_submit"] && $user_write)
		{
			post_topic($mysqli, $forum_id);
		}
		else if ($_GET["read_all_topics"] && $user_read)
		{
			read_all_topics($mysqli, $forum_id);
		}
		else if ($_GET["delete_topic"] && $user_write)
		{
			delete_topic($mysqli, $_GET["delete_topic"]);
		}
	}
	
	


	
	require "scripts/html_header.php";
?>
		<div id="container">
			<div class="titlebar">
				<ul class="nav">
					<li><a href="forum.php?forum_id=<?php echo $forum["forum_id"]; ?>"><?php echo $forum["forum_title"]; ?></a></li>
					<li><a href="index.php">Forums</a></li>
				</ul>
				<ul class="navicons">
				<?php
if ($is_logged_in && $user_write) // should check permissions in the future.
	{
?>
				<li><a href="javascript:alert_open('newtopicbox');" onmouseover="titletext('Create topic','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M387.628,238.223L202.581,423.267L49.945,461.139L90.641,311.01l184.887-184.888l28.352,28.351
L126.967,331.391l-10.901,45.092l20.288,20.287l46.166-10.148l176.755-176.752L387.628,238.223z M405.176,220.676l56.77-56.773
L349.845,51.805l-56.77,56.771L405.176,220.676z M321.35,171.896L146.656,346.587l20.506,20.506l174.693-174.691L321.35,171.896z"/> </svg>

				</a></li>
				<li><a href="forum.php?forum_id=<?php echo $forum_id; ?>&read_all_topics=1" onmouseover="titletext('Mark all topics read','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M171.941,291.333l14.721-13.609c17.22,8.332,28.139,14.672,47.493,28.469
c36.384-41.29,60.425-62.238,105.113-90.041l4.79,11.018c-36.853,32.161-63.843,67.982-102.708,137.68
C217.38,336.616,201.381,318.621,171.941,291.333z M432.816,109.791V462H79.184V109.791h79.888l-40.317,39.904v271.732h273.49
V149.695l-39.729-39.904H432.816z M308.232,108.902v-7.047c0-28.639-23.216-51.855-51.855-51.855
c-28.639,0-51.854,23.217-51.854,51.855v7.047l-56.612,57.086h217.18L308.232,108.902z M256.753,118.166
c-9.842,0-17.82-7.979-17.82-17.822c0-9.842,7.979-17.82,17.82-17.82c9.845,0,17.823,7.979,17.823,17.82
C274.576,110.188,266.598,118.166,256.753,118.166z"/> </svg>

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
				if ($is_logged_in){
					echo 
						'<div id="new_topic" style="display:none">
							<form action="forum.php?forum_id='.$forum_id.'" method="post" id="new_topic_form">
								Subject: <input type="text" name="topic_subject" /><br>
								Body: <textarea cols="40" rows="5" name="topic_body"></textarea><br>
								<!--<a href="new_topic.php?forum_id=1" id="new_topic_post">Post</a>-->
								<input type="submit" name="topic_submit" value="Post"/>
							</form>
						</div>';
				}
			?>
			
			<!-- Topics printed here -->
					<?php
						if ($forum)
						{
							if ($is_logged_in)
							{
								if ($user_read)
								{
									print_topics($mysqli, $forum_id);
								}
								else
								{
									echo "You do not have permission to read this forum.";
								}
							}
							else
							{
								/*If guests can read this forum*/
								if ($forum["forum_read"] == '3')
								{
									print_topics($mysqli, $forum_id);
								}
								else
								{
									echo "You do not have permission to read this forum.";
								}
							}
						}
					?>
			<div class="activity">
			<span>Users seen browsing this forum:</span> <a href="user.php?user_id=2">Cheesycarrion</a>, <a href="user.php?id=46">Bob</a>
			</div>
		</div>
<?php
	require "scripts/html_footer.php";
	require "scripts/footer.php";
?>
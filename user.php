<?php
	$additional_stylesheets = array("user.css");
	$additional_scripts = array("sha512.js","forms.js","jquery-1.11.1.min.js","user.js", "jquery.autosize.js", "jquery.autosize.min.js");
	require "scripts/header.php";
	require "scripts/html_header.php";
	
	$user_id = strint($_GET["user_id"]);
	
	$user_query = "SELECT user_id, user_name, user_timestamp, user_numpost FROM user WHERE user_id = ".$user_id." LIMIT 1";
	
	$user_result = $mysqli->query($user_query) or die("Error: " . $mysqli->error);
	
	$user = $user_result->fetch_array();
	
?>
<div id="container">
	<div class="titlebar">
		<ul class="nav">
			<li><a href="user.php?user_id=<?php echo $user["user_id"]; ?>"><?php echo $user["user_name"]; ?></a></li>
			<li><a href="user.php">Users</a></li>
		</ul>
		<ul class="navicons">
				<?php
if ($is_logged_in && $userdata["user_id"] == $user_id) // should check permissions in the future.
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
		if ($user)
		{
	?>
	<div id="userdata">
		<span id="username"><?php echo $user["user_name"];?></span>
		<br>
		<img id="userpic" src="img/snoop.gif">
		<br>
		<span id="userjoined"><?php echo $user["user_timestamp"];?></span>
		<br>
		<span id="usernumposts"><?php echo $user["user_numpost"];?></span>
	</div>
	<div id="userposts">
		<!--User blog posts go here-->
	</div>
	<?php
		}
		else
		{
			$all_user_query = "SELECT user_id FROM user;";
			$all_user_result = $mysqli->query($all_user_query) or die("Error:" . $mysqli->error);
			$num_all_user = mysqli_num_rows($all_user_result);
			$user_per_page = 5;
			$start = 0;
			$start += strint($_GET["start"]);
			
			pagiation($start, $user_per_page, $num_all_user);
			
			$user_query = "
				SELECT 
					user_id, user_name
				FROM
					user
				ORDER BY
					user_name
				LIMIT "
					. $start . ", " . $user_per_page . ";";
			
			$user_result = $mysqli->query($user_query) or die("Error:" . $mysqli->error);
	?>
	<div id="userlist">
		<div id="userlisthead">
			<span>Username</span>
		</div>
		<ul>
			<?php
				while ($user = $user_result->fetch_array()){
				?>
					<li>
						<a href="<?php echo getlink(array(), array(user_id), array($user["user_id"])); ?>"><?php echo $user["user_name"];?></a>
					</li>
				<?php
					
				}
			?>
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
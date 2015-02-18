<?php
/**********************************************************************

	html_header.php
	
	This is where html is first output. It must come after header. There
	are a few variables which may be set before including this file, 
	such as the array $additional_stylesheets.

***********************************************************************/

require_once "kill.php";

if (!isset($additional_stylesheets)) $additional_stylesheets = array();
if (!isset($additional_scripts)) $additional_scripts = array();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Stone Razor</title>
<link rel="stylesheet" type="text/css" href="style/default.css">
<?php
foreach ($additional_stylesheets as $val) echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style/" . $val . "\">\n";

if (!$is_logged_in) echo "\n<script type=\"text/JavaScript\" src=\"js/sha512.js\"></script>\n<script type=\"text/JavaScript\" src=\"js/forms.js\"></script>";
?>
<script type="text/JavaScript" src="js/alert.js"></script>
<?php
foreach ($additional_scripts as $val) echo "<script type=\"text/javascript\" src=\"js/" . $val . "\"></script>\n";
?><script type="text/javascript" language="javascript"><!--

function titletext(text,num)
	{
	document.getElementById('titletext_' + num).innerHTML = text;
	}

//--></script>
</head>
<body>
<div id="metametacontainer">
	<div id="alertframe">
		<a href="javascript:alert_close();">&nbsp;</a>
	</div>
<?php
if (!$is_logged_in)
	{
?>

	<div id="loginbox">
		<form action="<?php echo getlink(array("op"),array("op"),array("1")); ?>" method="post" name="login_form">
			<p class="logintext">Login</p>                    
			<p>Email: <input type="text" name="email"></p>
			<p>Password: <input type="password" name="password" id="password"></p>
			<p><input type="button" value="Login" onclick="formhash(this.form, this.form.password);"></p>
		</form>
	</div>
	<div id="registerbox">
		<form action="<?php echo getlink(array("op"),array("op"),array("3")); ?>" method="post" name="registration_form">
			<p class="logintext">Register</p>
			<p>Username: <input type="text" name="username" id="username"></p>
			<p>Email: <input type="text" name="email" id="email"></p>
			<p>Password: <input type="password" name="password" id="password"></p>
			<p>Confirm: <input type="password" name="confirmpwd" id="confirmpwd"></p>
			<p><input type="button" value="Register" onclick="return regformhash(this.form,this.form.username,this.form.email,this.form.password,this.form.confirmpwd);"></p> 
		</form>
	</div>
<?php
	}
if ($newtopicbox_on)
  {
?>
	<div id="newtopicbox">
	  <form action="forum.php?forum_id=<?php echo $forum_id; ?>" method="post" name="newtopic_form">
	    <p class="logintext">New Topic</p>
			<p class="subject_p">Subject: <input type="text" name="topic_subject"></p>
			<p class="topic_body_p">Body: <textarea cols="40" rows="5" name="topic_body"></textarea></p>
			<p class="button_p"><input type="submit" name="topic_submit" value="Post" class="button"><input type="reset" onclick="alert_close();" name="Cancel" value="Cancel" class="button"></p>
		</form>
	</div>
<?php
	}
?>
	<div id="header">
		<ul id="header_right">
<?php
if ($is_logged_in)
	{
?>
			<li><a href="user.php?user_id=<?php echo $userdata["user_id"];?>"><!-- used to have id="loginout" --><?php echo htmlentities($_SESSION['username']); ?></a>
				<ul>
					<li><a accesskey="p" href="user.php?user_id=<?php echo $userdata["user_id"];?>"><span class="shortcut">Alt + P</span>Profile</a></li>
					<li><a accesskey="," href="settings.php"><span class="shortcut">Alt + ,</span>Account Settings</a></li>
					<li><a accesskey="q" href="<?php echo getlink(array("op"),array("op"),array("2")); ?>"><span class="shortcut">Alt + Q</span>Logout</a></li>
				</ul>
			</li>
<?php
	}
else
	{
?>
			<li><a accesskey="l" href="javascript:alert_open('loginbox');">Log In</a></li>
			<li><a href="javascript:alert_open('registerbox')">Register</a></li>
<?php
	}
?>
			<li class="icon32"><a href="javascript:void();">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M460.355,421.59L353.844,315.078c20.041-27.553,31.885-61.437,31.885-98.037
C385.729,124.934,310.793,50,218.686,50C126.58,50,51.645,124.934,51.645,217.041c0,92.106,74.936,167.041,167.041,167.041
c34.912,0,67.352-10.773,94.184-29.158L419.945,462L460.355,421.59z M100.631,217.041c0-65.096,52.959-118.056,118.055-118.056
c65.098,0,118.057,52.959,118.057,118.056c0,65.096-52.959,118.056-118.057,118.056C153.59,335.097,100.631,282.137,100.631,217.041
z"/> </svg>

			</a></li>
		</ul>
		<a id="logo" href="index.php">Stone Razor</a>
		<ul id="header_left">
			<li><a href="index.php" class="selected">Forums</a>
				<ul>
					<li><a accesskey="1" href="forum.php?forum_id=3"><span class="shortcut">Alt + 1</span>General Conversation</a></li>
					<li><a accesskey="2" href="forum.php?forum_id=1"><span class="shortcut">Alt + 2</span>Random Bullshit</a></li>
					<li><a accesskey="2" href="forum.php?forum_id=4"><span class="shortcut">Alt + 3</span>Development</a></li>
				</ul>
			</li>
			<?php
			if ($is_logged_in)
			{
			?>
			<li class="newmsg"><a accesskey="m" href="msg.php">Messages</a>
				<ul>
					<li class="unread"><a href="msg.php?msg_id=52">Unread message</a></li>
					<li class="read"><a href="msg.php?msg_id=483">Read message</a></li>
				</ul>
			</li>
			<li><a accesskey="f" href="fr.php">Friends</a>
				<ul>
					<li><a class="nohover">You have no friends.</a></li>
					<li><a href="user.php">All users</a></li>
				</ul>
			</li>
			<?php
			}
			?>
		</ul>&nbsp;
	</div>
	<div id="metacontainer">
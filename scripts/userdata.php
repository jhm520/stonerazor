<?php
/*************************************************

userdata.php

Defines a global variable with userdata of client.
Also defines a function for finding userdata of a
specified user.

**************************************************/


/*
if (login_check($mysqli))
	{
	$query = "SELECT * FROM user WHERE user_id=" . $_SESSION["user_id"] . " LIMIT 1";
	$result = $mysqli->query($query);
	$userdata = $result->fetch_array(MYSQLI_ASSOC);
	}
else $userdata["user_permission"] = 3; // set guest permissions

unset($query,$result);
*/

// and now for other users:

$userdata = array();

function userdata($user_id)
	{
	global $mysqli;
	$query = "SELECT * FROM user WHERE user_id=" . $user_id . " LIMIT 1";
	$result = $mysqli->query($query);
	return $result->fetch_array(MYSQLI_ASSOC);
	}
	
	if (login_check($mysqli)) $userdata = userdata($_SESSION["user_id"]);
	else $userdata["user_permission"] = 3;
	

?>

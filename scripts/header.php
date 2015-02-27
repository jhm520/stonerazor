<?php
/**********************************************************************

	header.php
	
	read readme.txt for general information.
	
	This is the php file that will include things that will be needed
	on every page load. For example, one of the first things in this
	file will be our database connection. We will include scripts where
	we define functions that are used across the site. This page
	will not contain html, but we can set global variables here that
	can be referenced later on.
	
	Avoid redundancies by requiring scripts in a smart order. Notice,
	for example, that getlink.php uses the strint() function. So it is
	required after strint.php.

***********************************************************************/

require_once "kill.php";

// Put global variables here

$root = "/home3/croston/public_html/";

date_default_timezone_set("America/Los_Angeles"); // LA, wtf?

// Put site-wide requires here. Many of these will just define a function.
// To the right of each require, in a comment, note the script appearing
// furthest down on the list that the given script requires BEFORE it!!!!!


require "session_start.php";													// I think this should be always first.
require "database_connect.php";
require "login_check.php";														// session_start.php, database_connect.php, embedded_logout.php*
require "strint.php";
require "getlink.php";															// strint.php
require "pagiation.php";														// getlink.php
if (!$is_logged_in&&$_GET["op"]=="3") require "embedded_register.php"; 			// login_check.php
if (!$is_logged_in&&$_GET["op"]=="1") require "embedded_login.php"; 			// login_check.php
if ($is_logged_in&&$_GET["op"]=="2") require "embedded_logout.php"; 			// login_check.php
require "userdata.php";															// login_check.php, embedded_login.php*
require "post_handler.php";														// userdata.php, strint.php
require "datetime_interpreter.php";



// * if they are triggered



//session_start();
?>

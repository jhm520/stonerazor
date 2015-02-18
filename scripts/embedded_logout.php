<?php
/****************************

embedded_logout.php

*****************************/

require_once "kill.php";
 
// Unset all session values 
$_SESSION = array();
 
// get session parameters 
$params = session_get_cookie_params();
 
// Delete the actual cookie. 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Destroy session 
session_destroy();

$is_logged_in = login_check($mysqli); // see comment at the top of login_check.php to see why this is here.
?>
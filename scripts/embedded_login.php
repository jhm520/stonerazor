<?php
/********************************************

I just took login_script.php from the root
and put it in the scripts dir with some
alterations to make it work on any page.
Also required kill.php. This could probably
ultimately be combined with login_script.php.

*********************************************/
require_once "kill.php";
require "login_script.php";
 
// Our custom secure way of starting a PHP session.
 
if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // The hashed password.

    if (login($email, $password, $mysqli) == true) {
        // Login success
    } else {
        // Login failed 
        header('Location: '.getlink(array(),array("error"),array("1")).'');
    }
}

$is_logged_in = login_check($mysqli); // see comment at the top of login_check.php to see why this is here.
?>

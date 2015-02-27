<?php
/*****************************************

login_check.php

Update by Sam on 3/3/14: 

Added a global variable $is_logged_in which
can be referenced rather than calling the
function over and over (which does a mysql
query each time). The function's original
functionality is preserved, so it still
returns a bool for whether you're logged in
for the sake of backwards-compatibility. 
The original aforementioned functionality
should be preserved in the future because
the login state may change if the user logs
in or out. In this case, embedded_login.php
or _logout.php will be called in the header.
It is useful to have the login_check()
function defined before this process as well.
Thus at the end of embedded_login.php and
_logout.php the function will be called so
that the global variable may be used
thereafter. For a similar reason, I also
modified this file to call the function
immediately after it is defined so that the
new global variable can be used.
I also made a global called $login_error
which can be echoed when you log in. 
I haven't made those errors appear yet but
it could be done...

******************************************/

require_once "kill.php";

$login_error = "";
$is_logged_in = false;

function login_check($mysqli) {
	global $login_error;
	
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT user_password 
                                      FROM user
                                      WHERE user_id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logged In!!!! 
					//$is_logged_in = true;
                    return true;
                } else {
                    // Not logged in 
					$login_error = "Hash not match";
					//$is_logged_in = false;
                    return false;
                }
            } else {
                $login_error = "an error occured";
				//$is_logged_in = false;
                return false;
            }
        } else {
            // Not logged in 
			$login_error = "Statement failed: ". $mysqli->error;
			//$is_logged_in = false;
            return false;
        }
    } else {
        // Not logged in 
		//$is_logged_in = false;
        return false;
    }
}

$is_logged_in = login_check($mysqli);

?>

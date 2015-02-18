<?php
/*********************************************************************

	kill.php
	
	read readme.txt for general information.
	
	This script should be included at the top of every other script.
	
	YOU NEED TO USE   REQUIRE_ONCE   TO INCLUDE THIS FILE!!!!
	
	It will make it so the script can't be executed by randys. If you
	are working on a script, set the variable $dev to TRUE before
	including this file. Note that php variables are global by default.
	See: http://php.net/manual/en/language.variables.scope.php
	You will also need to put your ip address in here to be able to
	make it work. Remember to get rid of your $dev declaration when
	you're done
	
**********************************************************************/

if (!isset($dev)) $dev = FALSE;
if (substr_count($_SERVER["PHP_SELF"],"/",1)==1 && !$dev) die("Access denied.");

// ^ This checks to see the number of slashes in the url you used to access the page.
// If you are not in the root, it dies.
?>
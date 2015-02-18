<?php
/********************************************************

strint.php

This script defines a function that takes a string
(usually originating from get, post, session data) and
checks if it contains a positive integer. If it does, it
returns itself casted as an integer. If it doesn't, it
returns 0 or FALSE depending on the flag.

Note: usually this function is used to verify numbers
recieved by the user in order to put them into database
queries. Moreover, the fields that are stored as integers
where data will be recieved from users are generally the
auto-incrementing primary keys. These fields start at 1,
not 0.

Therefore under normal circumstances, it is sufficient to
write if-statements like:

if strint($string) {}

I set the default $fail_return_type to 0 instead of FALSE
so that the function will always return integer values.
So you can make your mysql queries like

$query = "... WHERE user_id='" . strint($string) . "'...";



strint.php must be required BEFORE getlink.php in
header.php !!!!!!!!!!!!!!!!!!

*********************************************************/

require_once "kill.php";

function strint($input,$max=0,$fail_return_type=0)
	{
	if (
	
	(int)$input == $input && // eliminates most types of #s
	is_numeric($input) && // because apparently "cow"==0
	(int)$input >= 0 && // must be natural number
	((int)$input <= $max || $max == 0) // check max unless 0
	
	) $return = (int)$input;
	elseif ($fail_return_type==0) $return = 0;
	elseif ($fail_return_type==FALSE) $return = FALSE;
	return $return;
	}

?>
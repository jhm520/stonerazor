<?php
/****************************************************************************

getlink.phpThis script defines a function that takes get data and formulates
a url with the same get data up to specified exceptions. It is included in
header.php

Also in this file is a catalog of possible get variables. When a new get
variable is added, add it to this file. Also add the types and maximum sizes

WARNING: There is some sort of ghost or daemon possessing this file. Line break
characters get added and removed sometimes and appear differently in notepad
or notepad++, and when you save it sometimes deletes them. I finally got it
working (again) but be careful and make backups. It's only this file for some
reason I've had the problems with.

*****************************************************************************/

require_once "kill.php"; // use $_GET and $_SERVER["PHP_SELF"]

function getlink($omit = array(), $add_var = array(), $add_val = array())
{
	if (!is_array($omit) || !is_array($add_var) || !is_array($add_val)) die("the parameters of getlink() all need to be arrays");
	if (count($add_var)!=count($add_val)) die("you need to put the same amount of things in add_var as add_val (2nd and 3rd parameters of getlink())");

	// Get variable name catalog
	$var_catalog = array(
	"forum_id",
	"fr_id",
	"msg_id",
	"post_id",
	"topic_id",
	"user_id",
	"op",
	"start",
	"read_all_topics"
	);

	// Get variable type catalog
	// Variable type names, as by php convention are:
	// booleen, integer, double, string, array, object, resource, NULL, unknown type
	// a few of these names have alternates (ineger, int) but let's use the ones above.
	// This is a good choice because these are the outputs of gettype();

	$type_catalog = array(
	"integer",
	"integer",
	"integer",
	"integer",
	"integer",
	"integer",
	"integer",
	"integer",
	"integer"
	);

	// Get variable max size catalog
	// The size is a character length unless the type is (positive) integer where it is just the number
	
	$size_catalog = array(
	2147483647,
	2147483647,
	2147483647,
	2147483647,
	2147483647,
	2147483647,
	2147483647,
	2147483647,
	1
	);

	$return = $_SERVER["PHP_SELF"];	$delimiter = "?";

	foreach ($_GET as $getvar => $getval)
	{
		$src = array_search($getvar,$var_catalog);
		if ($src !== FALSE) // Note: $src is 0 if we are using 0th element of array. So we have to use strict inequality.
		{
			$loop_key = $type_catalog[$src];
			//if ($loop_key == "integer" && (int)$getval == $getval && (int)$getval >= 0 && (int)$getval <= $size_catalog[$src]) // get variable type is integer
			if ($loop_key == "integer" && strint($getval,$size_catalog[$src])) // get variable type is integer
			{
				//$loop_val = (int)$getval;
				$loop_val = strint($getval);
				if (!in_array($getvar,$omit))
				{
					$return .= $delimiter . $getvar . "=" . $loop_val;
					$delimiter = "&amp;";
				}
			}
			elseif ($loop_key != "integer") // get variable type is not integer
			{
				$loop_val = $getval;
				if (!in_array($getvar,$omit))
				{
					$return .= $delimiter . $getvar . "=" . $loop_val;
					$delimiter = "&amp;";
				}
			}
		}
	}
	foreach ($add_var as $key => $val)
	{
		$return .= $delimiter . $val . "=" . $add_val[$key];
		$delimiter = "&amp;";
	}
	return $return;
}
?>
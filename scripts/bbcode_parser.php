<?php
/**************************************
* bbcode_parser.php
*
* defines a function that turns bbcode into html
***************************************/

function bbcode_parser($haystack,$slashes = true)
	{
	if ($slashes) $haystack = stripslashes($haystack);
	
	$nedle = array(
	"/\[b\](*.?)\[\/b\]/";
	"/\[i\](*.?)\[\/i\]/";
	"/\[u\](*.?)\[\/u\]/";
	"/\[s\](*.?)\[\/s\]/";
	"/\[img\](*.?)\[\/s\]/"
	"/\[url=(*.?)\](*.?)\[\/url\]/";
	);
	
	$replacements = array(
	"<b>\\1</b>";
	"<i>\\1</i>";
	"<u>\\1</u>";
	"<s>\\1</s>";
	"<img src=\"\\1\" alt=\"\">";
	"<a href=\"\\1\">\\2\</a>";
	);
	
	}
?>

<?php
/***************************************************

This script makes a function that outputs the html
pagiation, given certain input parameters.

It assumes that $_GET["start"] is the index of
pagiation.

WARNING: this function ECHOes text. It does not return!

****************************************************/

function pagiation($start,$ipp = 15,$num_rows, $loadstart=0, $ppl=10, $john=False)
{
	
	
	
	if ($john){
		echo "<div class=\"pagiation\">\n";
		if ($start > 0) echo "<a name=\"".($start-$ipp)."\" class=\"newer\" href=\"javascript:void();\">&larr; Newer Content</a>\n"; // strint it so it doesn't go negative
		else echo "<span class=\"newer\">&larr; Newer Content</span>\n";
		
		if ($num_rows > $start+$ipp) echo "<a name=\"".($start+$ipp)."\" class=\"older\" href=\"javascript:void();        \">Older Content&rarr;</a>\n";
		else echo "<span class=\"older\">Older Content &rarr;</span>\n";
		
		echo "<div class=\"numbers\">\n";
		
		for ($i=0;$i*$ipp<$num_rows;$i++)
		{
			if ($start==$i*$ipp) $selected = " selected";
			else $selected = ""; 
			if ($i*$ipp<$loadstart)
			{
				echo "<a name=\"". ($i*$ipp) ."\" class=\"number" . $selected . "\" href=\"" . getlink(array("start","op"),array("start"),array($i*$ipp)) . "\">" . ($i+1) . "</a>"; // NO WHITESPACE FOR CSS!!!

			}
			else if ($i*$ipp>=$loadstart+($ppl*$ipp))
			{
				echo "<a name=\"".($i*$ipp) ."\" class=\"number" . $selected . "\" href=\"" . getlink(array("start","op"),array("start"),array($i*$ipp)) . "\">" . ($i+1) . "</a>"; // NO WHITESPACE FOR CSS!!!
			}
			else
			{
				echo "<a name=\"".($i*$ipp) ."\" class=\"number" . $selected . "\" href=\"javascript:void();\">" . ($i+1) . "</a>"; // NO WHITESPACE FOR CSS!!!
			}
			//echo "<a class=\"number" . $selected . "\" href=\"" . getlink(array("start","op"),array("start"),array($i*$ipp)) . "\">" . ($i+1) . "</a>"; // NO WHITESPACE FOR CSS!!!
		}
		
		echo "</div>\n";
		echo "</div>\n";
	}
	else
	{
		echo "<div class=\"pagiation\">\n";
		
		// NEXT/PREV BUTTONS
		
		if ($start > 0) echo "<a class=\"newer\" href=\"" . getlink(array("start","op"),array("start"),array(strint($start-$ipp))) . "\">&larr; Newer Content</a>\n"; // strint it so it doesn't go negative
		else echo "<span class=\"newer\">&larr; Newer Content</span>\n";

		if ($num_rows > $start+$ipp) echo "<a class=\"older\" href=\"" . getlink(array("start","op"),array("start"),array($start+$ipp)) . "\">Older Content&rarr;</a>\n";
		else echo "<span class=\"older\">Older Content &rarr;</span>\n";
		
		// NUMBERS
		
		echo "<div class=\"numbers\">\n";
		
		$i = 0;
		while ($i*$ipp<$num_rows)
			{
			if ($start==$i*$ipp) $selected = " selected";
			else $selected = "";
			echo "<a class=\"number" . $selected . "\" href=\"" . getlink(array("start","op"),array("start"),array($i*$ipp)) . "\">" . ($i+1) . "</a>"; // NO WHITESPACE FOR CSS!!!
			
			$i++;
			}
		
		echo "</div>\n";
		echo "</div>\n";
	}
}

?>
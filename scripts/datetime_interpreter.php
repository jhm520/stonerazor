<?php
// says like "3 days ago" or something

function datetime_interpreter($input)
	{
	$current_datetime = new DateTime();
	$input_datetime = new DateTime($input);
	$input_datetime_diff = $current_datetime->diff($input_datetime);
	$input_datetime_array = array($input_datetime_diff->format("%s"),$input_datetime_diff->format("%i"),$input_datetime_diff->format("%h"),$input_datetime_diff->format("%d"),$input_datetime_diff->format("%m"),$input_datetime_diff->format("%y"));
	$input_datetime_text = "";
	$datetype_type_cycle = array("seconds","minutes","hours","days","months","years");
	foreach ($input_datetime_array as $key => $val)
	  {
	  if ((int)$val>0) $input_datetime_text = $val . " " . $datetype_type_cycle[$key] . " ago";
	  }
	return $input_datetime_text;
	}
?>
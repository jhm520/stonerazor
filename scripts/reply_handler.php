<?php
/**************************************************
*                                                 *
*                reply_handler.php                *
*                                                 *
* This function takes the body as raw post data,  *
* extracts any reply tags, puts replies in the db *
* and then removes the reply tag in the post. It  *
* then returns the string for further processing  *
*                                                 *
*              UNDER CONSTRUCTION:                *
*                                                 *
* I still haven't dealt with the db stuff yet.    *
* Namely, the reply table is going to be best     *
* looking like this:                              *
*                                                 *

  |  reply_id  |  replier_user_id  |  repliee_user_id  |  replier_post_id  |  repliee_post_id  |
--+------------+-------------------+-------------------+-------------------+-------------------|
  |  (PRIMARY) |       (INT)       |       (INT)       |        (INT)      |        (INT)      |

*                                                 *
* I also need to write code in below where I just *
* wrote my plans in comments.                     *
*                                                 *
* Then implementation will modify                 *
* forum_script.php and topic.php                  *
*                                                 *
***************************************************/

require_once "kill.php";

function reply_handler($string,$mysqli)
	{
	global $userdata;
	if (preg_match("/\>\>[0-9]+/",$string,$token))
		{
		
		$i = 0;
		$delimiter = "";
		$list = "(";
		
		while ($i<10) // only add maximum of 10 replies
			{
			
			if (isset($token[$i]) && strint($token[$i]))
				{
				$list .= $delimiter . $token[$i];
				}
			
			$i++;
			$delimiter = ",";
			}
		
		$list .= ")";
		
		// 
		
		return preg_replace("/\>\>[0-9]+/","",$string);
		}
	else return $string;
	}

?>
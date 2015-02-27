<?php
/**************************************************
*                                                 *
*                 post_handler.php                *
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

function post_handler($body,$item_id,$mode,$mysqli,$topic_subject="") // mode can be "topic", "post" or "update" (update is updating a post, else inserting)
	{
	$max_num_reply = 10; // maximum number of replies to accept
	
	global $userdata;
	
	$item_id = strint($item_id); // will be ("forum_id","topic_id","post_id") if $mode is set to ("topic","post","update")
	$date = date('Y-m-d H:i:s');
	$body_clean = preg_replace("/\>\>[0-9]+/","",$body); // remove reply tags
	$body_clean = $mysqli->real_escape_string($body_clean);
	$body = $mysqli->real_escape_string($body);
	
	//                  INSERT OR UPDATE                   //
	
	if ($mode=="topic") // Inserting new topic
		{
		$forum_id = $item_id;
		
		$topic_subject = $mysqli->real_escape_string(htmlentities($topic_subject));
		
		$prep_query = 
			"INSERT INTO 
				topic (user_id, topic_timestamp,
				topic_update, topic_subject, forum_id, topic_ip)
			VALUES 
				(" . $userdata["user_id"] . ", '" . $date . "', '"
				. $date . "', '" . $topic_subject . "', " . $forum_id
				. ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$query = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		// Now get the topic_id and create the post
		
		$topic_id = mysqli_insert_id($mysqli);
		
		$prep_query = 
			"INSERT INTO
				post (user_id, post_timestamp, post_update, 
				post_body, topic_id, post_ip)
			VALUES 
				(" . $userdata["user_id"] . ", '" . $date 
				. "', '" . $date . "', '" . $body_clean . "', "
				. $topic_id . ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$query = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		// Now get the post_id for the reply feature to use
		
		$replier_post_id = mysqli_insert_id($mysqli); // gets primary key of last insert/update
		}
	elseif ($mode=="post") // Inserting new post in pre-existing topic
		{
		$topic_id = $item_id;
		
		$prep_query = 
			"INSERT INTO
				post (user_id, post_timestamp, post_update, 
				post_body, topic_id, post_ip)
			VALUES 
				(" . $userdata["user_id"] . ", '" . $date 
				. "', '" . $date . "', '" . $body_clean . "', "
				. $topic_id . ", '" . $_SERVER['REMOTE_ADDR'] . "');";
		
		$query = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		$replier_post_id = mysqli_insert_id($mysqli); // gets primary key of last insert/update
		
		$prep_query = "UPDATE topic SET topic_update='".$date."' WHERE topic_id=".$topic_id;
		$query = $mysqli->query($prep_query) or die("Error:" . $mysqli->error);
		
		/*Delete all the readtopics from this topic, since this topic is being updated with a new post*/
		$delete_readtopic = "
			DELETE FROM
				readtopic
			WHERE
				topic_id=".$topic_id;
		
		$delete_readtopic_result = $mysqli->query($delete_readtopic) or die("Error:" . $mysqli->error);
		}
	elseif ($mode=="update") // Editing a post
		{
		$replier_post_id = $item_id;
		
		$prep_query = "UPDATE post SET post_body='".$body_clean."' WHERE post_id=".$replier_post_id." LIMIT 1;";
		$query = $mysqli->query($prep_query) or die("Error:". $mysqli->error);
		}
	// else uh oh
	
	// In all three of the above cases, we define the variable $replier_post_id
	
	//             REPLY FEATURE              //
	
	if (preg_match_all("/\>\>([0-9]+)/",$body,$token))
		{
		
		// The next two loops go through the same thing.
		// This is to make it so there are less queries.
		// Note: Haven't yet done this: First loop should
		// generate a single query, then execute, not do
		// individual queries.
		
		$i = 0;
		$repliee_user_id = array();
		
		while ($i<$max_num_reply)
			{
			// get the user ids of the people who are being replied to (repliee_user_id) based on their post id, (repliee_post_id=token_i)
			
			if (isset($token[1][$i]) && strint($token[1][$i]))
				{
			
				$prep_query = "SELECT user_id FROM post WHERE post_id='" . $token[1][$i] . "' LIMIT " . $max_num_reply;
				$query = $mysqli->query($prep_query) or die($mysqli->error);
				
				if (mysqli_num_rows($query)==1)
					{
					$post_id_data = $query->fetch_row();
					$repliee_user_id[$i] = $post_id_data[0];
					}
				}

			$i++;
			}
		
		$i = 0;
		$delimiter = "";
		$list = "";
		
		while ($i<$max_num_reply) // only add maximum of $max_num_reply replies
			{
			
			if (isset($token[1][$i]) && strint($token[1][$i]) && !empty($repliee_user_id[$i]))
				{
				// (replier_user_id, replier_post_id, repliee_user_id, repliee_post_id)
				$list .= $delimiter . "(" . $userdata["user_id"] . "," . $replier_post_id . "," . $repliee_user_id[$i] . "," . $token[1][$i] . ")"; 
				}
			
			$i++;
			$delimiter = ",";
			}
		
		if (!empty($list))
			{
			$prep_query = "
				INSERT INTO reply
				(replier_user_id, replier_post_id, repliee_user_id, repliee_post_id)
				VALUES
				" . $list . ";";
			
			$query = $mysqli->query($prep_query) or die($mysqli->error);
			}
		
		}
	
	if ($mode=="topic")
		{
		header("Location: topic.php?topic_id=" . $topic_id);
		exit;
		}
	
	return true;
	}

?>

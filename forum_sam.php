<?php
/*

Please don't edit if you are not Sam.





To do:

* new topic button
* mark all read button
* permissions (do it right).
* usernames from user id (separate script)









*/

require "scripts/header.php";

// Get forum data. If invalid forum, redirect to index. Note you will always have been linked from index and it will be like forum.php?forum_id=???.

$forum_id = strint($_GET["forum_id"]); // important that it returns 0 on non-positive integers.
$query_4 = "SELECT * FROM forum WHERE forum_id=" . $forum_id . " LIMIT 1";
$result_4 = $mysqli->query($query_4);
$forumdata = $result_4->fetch_array(MYSQL_ASSOC);

if (empty($forumdata))
	{
	header("Location: index.php");
	die();
	} // else continue on.

$additional_stylesheets = array("topicOld.css");
require "scripts/html_header.php";

$start = strint($_GET["start"]);
$ipp = 6; // items per page.

// I have made it so we don't use joins but two different queries instead. This is because
// we need to match the user id in the readtopic table to the client's user id, not the 
// topic poster's. We can't put a "WHERE user_id=..." statement in because then we would get
// no data if the user hasn't read the post. So the first query will get topic data and second
// will get readtopic data. Then I'll do a third query to get only the number of topics.

$mysql_array = array(); // key: integer list; val: array with keys as column names, vals as data
$readtopics = array(); // key: topic_id; val: bool

$query_1 =  "SELECT * FROM topic WHERE forum_id=" . $forum_id . " ORDER BY topic_update DESC LIMIT " . $start . "," . $ipp;
$result_1 = $mysqli->query($query_1);

$topic_id_list = "";
$token = "";

while ($row = $result_1->fetch_array(MYSQLI_ASSOC))
	{
	$mysql_array[] = $row;
	
	$topic_id_list .= $token . $row["topic_id"];
	$token = ",";
	
	$readtopics[$row["topic_id"]] = FALSE; // go back later to mark certain ones as true.
	}

unset($row);

// 2nd query: Find read posts

if (login_check($mysqli) && !empty($topic_id_list)) // check !empty $topic_id_list not to fuck up mysql query
	{
	$query_2 = "SELECT topic_id FROM readtopic WHERE user_id=" . $userdata["user_id"] . " AND topic_id IN (" . $topic_id_list . ") LIMIT " . $ipp;
	$result_2 = $mysqli->query($query_2);

	while ($row = $result_2->fetch_array(MYSQLI_ASSOC)) $readtopics[$row["topic_id"]] = TRUE;

	unset($row);
	}

// 3rd query: Find number of topics in forum in an efficient way. Required for pagiation.

$query_3 = "SELECT topic_id FROM topic WHERE forum_id=" . $forum_id; // select minimal data.
$result_3 = $mysqli->query($query_3);
$num_rows = $result_3->num_rows;

?>

	<div id="container">
		<div class="titlebar">
			<ul class="nav">
				<li><a href="forum.php?forum_id=<?php echo $forum_id; ?>"><?php echo $forumdata["forum_title"]; ?></a></li>
				<li><a href="index.php">Forums</a></li>
			</ul>
			<ul class="navicons">
<?php
if (login_check($mysqli)) // should check permissions in the future.
	{
?>
				<li><a href="new_topic.php?forum_id=<?php echo $forum_id; ?>" onmouseover="titletext('Create topic','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M387.628,238.223L202.581,423.267L49.945,461.139L90.641,311.01l184.887-184.888l28.352,28.351
L126.967,331.391l-10.901,45.092l20.288,20.287l46.166-10.148l176.755-176.752L387.628,238.223z M405.176,220.676l56.77-56.773
L349.845,51.805l-56.77,56.771L405.176,220.676z M321.35,171.896L146.656,346.587l20.506,20.506l174.693-174.691L321.35,171.896z"/> </svg>

				</a></li>
				<li><a href="mark read link" onmouseover="titletext('Mark all topics read','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M171.941,291.333l14.721-13.609c17.22,8.332,28.139,14.672,47.493,28.469
c36.384-41.29,60.425-62.238,105.113-90.041l4.79,11.018c-36.853,32.161-63.843,67.982-102.708,137.68
C217.38,336.616,201.381,318.621,171.941,291.333z M432.816,109.791V462H79.184V109.791h79.888l-40.317,39.904v271.732h273.49
V149.695l-39.729-39.904H432.816z M308.232,108.902v-7.047c0-28.639-23.216-51.855-51.855-51.855
c-28.639,0-51.854,23.217-51.854,51.855v7.047l-56.612,57.086h217.18L308.232,108.902z M256.753,118.166
c-9.842,0-17.82-7.979-17.82-17.822c0-9.842,7.979-17.82,17.82-17.82c9.845,0,17.823,7.979,17.823,17.82
C274.576,110.188,266.598,118.166,256.753,118.166z"/> </svg>

				</a></li>
<?php
	}
?>
				<li><a href="javascript:location.reload();" onmouseover="titletext('Refresh','1');" onmouseout="titletext('&nbsp;','1');">

<svg
version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path
d="M373.223,142.573l-37.252,37.253c-20.225-20.224-48.162-32.731-79.021-32.731
c-61.719,0-111.752,50.056-111.752,111.776c0,0.016,0-0.016,0,0h43.412l-69.342,69.315L50,258.871h42.514c0-0.008,0,0.006,0,0
c0-90.816,73.621-164.46,164.436-164.46C302.357,94.411,343.467,112.816,373.223,142.573z M462,253.129l-69.268-69.316
l-69.342,69.316h43.412c0,0.016,0-0.017,0,0c0,61.72-50.033,111.776-111.752,111.776c-30.859,0-58.797-12.508-79.021-32.731
l-37.252,37.253c29.758,29.757,70.867,48.162,116.273,48.162c90.814,0,164.436-73.644,164.436-164.459c0-0.007,0,0.008,0,0H462z"/> </svg>

				</a></li>
			</ul>
			<span class="titletext" id="titletext_1">&nbsp;</span>
		</div>
		<div class="titlefade">&nbsp;</div>
<?php
pagiation($start,$ipp,$num_rows); // HEY JOHN: Use this function on other pages too!! It will work automatically!! Just make sure to use "start" as your get var!!
?>
		<div id="topiclist">
			<ul>

<?php

foreach ($mysql_array as $row)
	{
	if ($readtopics[$row["topic_id"]]) $unread_class = " class=\"read\"";
	else $unread_class = " class=\"unread\"";
	echo "<li" . $unread_class . ">\n";
	echo "<ul>\n";
	echo "<li class=\"title\">num replies</li>\n";
	echo "<li class=\"reply\">Last reply by <a href=\"user.php?user_id=last reply user id\">last reply username</a> on " . date("n/j/y",$row["topic_update"]) . "</li>\n";
	echo "<li class=\"author\">Posted by <a href=\"user.php?user_id=" . $row["user_id"] . "\">" . userdata($row["user_id"])["user_name"] . "</a> on " . date("n/j/y",$row["topic_timestamp"]) . "</li>\n";
	echo "</ul><a href=\"topic.php?topic_id=" . $row["topic_id"] . "\">" . $row["topic_subject"] . "</a>\n";
	echo "</li>\n";
	}

unset($row);

?>

			</ul>
		</div>
<?php pagiation($start,$ipp,$num_rows); ?>
		<div class="activity">
		<span>Users seen browsing this forum:</span> <a href="user.php?user_id=2">Cheesycarrion</a>, <a href="user.php?id=46">Bob</a>
		</div>
	</div>

<?php

require "scripts/html_footer.php";
require "scripts/footer.php";

?>

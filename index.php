<?php
	/*
		
	
	 */
	$additional_stylesheets = array("mainindex.css");
	require "scripts/header.php";
	require "scripts/html_header.php";
?>
	<!--
	<div>
		<a href="forum.php?forum_id=1">Random Bullshit</a>
	</div>
	<h1>welcome to the site where its alright</h1>
	<img style="height: 500px;" src="img/obey.png" alt="obey"/>
	<img style="height: 500px;" src="img/snoop.gif" alt="snoop"/>
	<audio controls>
		<source src="sound/smokeweed.mp3" type="audio/mpeg">
		Your browser does not support the audio element.
	</audio>
	-->
	<div id="container">
			<div class="titlebar">
				<ul class="nav">
					<li><a id="title_main" href="index.php">Forums</a></li>
				</ul>
				<ul class="navicons">
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
			<div id="forumlist">
			  <ul>
			    <li>
			      <a id="gen" href="forum.php?forum_id=3">General</a>
			    </li>
			    <li>
			      <a id="rbs" href="forum.php?forum_id=1">Random</a>
			    </li>
			    <li>
			      <a id="dev" href="forum.php?forum_id=4">Development</a>
			    </li>
			  </ul>
			</div>
	</div>
	
<?php
	require "scripts/html_footer.php";
	require "scripts/footer.php";
?>

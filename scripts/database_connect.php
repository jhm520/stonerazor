<?php
	
	require_once "kill.php";
	/*
		We don't need this.
	class Database extends mysqli {
		
	}
	*/
	
	$mysqli = new mysqli("stonerazor.com", "croston_sam", "cheesycarrion", "croston_db");
	
	/*
	$query = "SELECT * FROM forum";
			
	$query = $database->multi_query($query) or die("Error: " . mysqli_error($database) . "<br>");
	
	if ($result = $database->use_result()){
		while ($row = $result->fetch_array()){
			echo $row['forum_id'] . " " . $row['forum_title'];
			echo "<br />";
		}
	}
	*/

	/*
	if ($database){
		$database->close();
		echo "Database closed.";
	}
	*/
	

?>

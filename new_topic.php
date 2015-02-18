<?php
//This page doesn't do shit yet.
	require "scripts/header.php";
	require "scripts/html_header.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src= "js/forms.js"></script>
        <link rel="stylesheet" href="styles/main.css" />
    </head>
    <body>
		<form action="new_topic.php" method="POST" name="login_form">
			Subject: <input type="text" name="subject" />
            Body: <textarea cols="40" rows="5" name="body"></textarea>
           <input type="submit" name="submit" value="Submit"/>
		</form>
	</body>
</html>

<?php
	require "scripts/html_footer.php";
	require "scripts/footer.php";
?>
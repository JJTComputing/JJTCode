<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Get the user's code
$code=$_GET['code'];

// Check they aren't being malicious
if (preg_match("[^0-9a-zA-Z]", $code))
{
	echo '<h2>Error</h2>';
	echo '<p>Your code is invalid</p>';
}

else
{
	// Proceed with the MySQL check!
	$query="SELECT * FROM user_validation WHERE code = '$code'";
	$result=mysql_query($query);
	
	// If we do not have a match, tell the user
	if (mysql_num_rows($result)==0)
	{
        echo '<h2>Error</h2>';
		echo '<p>Your code is invalid</p>';
	}
	
	else
	{
		// If we have a valid code, update the user's level!
		$user=mysql_result($result, 0, "login");
		$query="UPDATE users SET level = '2' WHERE login = '$user'";
		mysql_query($query);

		// We don't need the validation code anymore, so lets save some space and delete it
		$query="DELETE FROM user_validation WHERE code = '$code'";
		mysql_query($query);
		
		echo '<p>Thank you for confirming your email address. You will now have the updated privledges of a full user.
		Thank you for using JJTCode!</p>';
	}
}
		
	
	 

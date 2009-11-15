<?php
 /* login_error.php - JJTCode
  * 
  * Purpose:
  * To show the errors that occur during logins to the user in a friendly way
  * 
  * Receives From:
  * 1. login.php - the error that occured via GET
  * 
  * Sends To:
  * 
  * Requires Login: NO
  * 
  */ 
   
if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

echo '<h2>Error</h2>';

echo '<h3>';

// Test the error
switch ($_GET['error'])
{
	// IP is banned
	case "ip":
	echo 'You have exceeded the maximum amount of logins. Please wait an hour before trying again.';
	break;
	
	// Username/Password is incorrect
	case "username":
	echo 'The given username/password is incorrect';
	break;
	
	// If nothing else!
	default:
	echo 'An error has occured. Please try again later';
	break;
}
?>

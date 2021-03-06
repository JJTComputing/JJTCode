<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// If the user is logged in, they do not need to register!
if (login_check())
{
	// If they are logged in, show an error!
	echo '<h2>Error</h2><p>You are already logged in, you do not need to register!</p>';
}
else
{
// Here we register the user into the MySQL database

// Perform some validation
/*
// BotScout!
$error="";
$APIKEY = 'm7XobnTLhfGAPvC';

$XIP = $_SERVER['REMOTE_ADDR']; 
$XUSER = $_POST['username'];
$XMAIL = $_POST['email'];

// make the url compliant with urlencode()
$XMAIL =urlencode($XMAIL);


// run the API query...the default is to check the email address. It's usually the most 
// reliable indicator or bot 'signature' field, but you can change this to use the Ip or 
// the username if you like. You could check all three if you wanted, but usually the 
// email address alone is sufficient. 


// testing for an email address and IP
$apiquery = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP&key=$APIKEY";

// Get the data from the Bot Scout system
$returned_data = file_get_contents($apiquery);

// take the returned value and parse it (standard API, not XML)
$botdata = explode('|', $returned_data); 

// sample 'MULTI' return string (standard API, not XML)
// Y|MULTI|IP|4|MAIL|26|NAME|30

// if the first character is an exclamation mark, an error has occurred 
if(substr($returned_data, 0,1) == '!'){ 
	$error.="An Error has occured. Please try again.";
}


// this example tests the email address and IP to see if either of them appear 
// in the database at all. Either one is a fairly good indicator of bot identity. 

if($botdata[3] > 0 || $botdata[5] > 0){ 
	$error.="Our system has identified you as a SpamBot. If you are not a bot please contact the administrator.";
}
*/
// Make sure there aren't any empty values
if (empty($_POST['pass1']) || empty($_POST['pass2']))
{
	$error.="The password field is empty! ";
}

if (empty($_POST['username']))
{
	$error.="The username is empty! ";
}

if (empty($_POST['login']))
{
	$error.="The login name is empty! ";
}

if (empty($_POST['email']))
{
	$error.="The email field is empty! ";
}

// If the error variable is not empty, then there is no point continuing, as it would be a waste of resources
if (!empty($error))
{
	echo '<p class="warning">'.$error.'</p>';
}
// But if the error variable is empty, then lets do some proper validation!
else
{
	// Check the username for invalid characters
	if (preg_match("[^0-9a-zA-Z]", $_POST['username']))
	{
		$error.="The username contains invalid characters! ";
	}
	
	if (preg_match("[^0-9a-zA-Z]", $_POST['login']))
	{
		$error.="The login contains invalid characters! ";
	}
	
	if (!check_email_address($_POST['email']))
	{
		$error.="The email address is invalid ";
	}
	
	// Make sure the passwords are the same
	if ($_POST['pass1']!=$_POST['pass2'])
	{
		$error.="The Entered Passwords Do Not Match! ";
	}
	
	if (!empty($error))
	{
		echo '<p class="warning">'.$error.'</p>';
	}
	
	else
	{
		// Now we check whether the login exists in the MySQL database
		$query="SELECT * FROM users WHERE login = '$_POST[login]'";
		$result=mysql_query($query);
		
		// If the row count is above 0 then there are matches, and the login is already in use!
		if (mysql_num_rows($result)>0)
		{
			$error.="The login already exists. ";
		}
		
		// Now do the same for the username!
		$query="SELECT * FROM users WHERE username = '$_POST[username]'";
		$result=mysql_query($query);
		
		// Again, if the row count is above 0, we have matches!
		if (mysql_num_rows($result)>0)
		{
			$error.="The username already exists!";
		}
		
		if (!empty($error))
		{
			echo '<p class="warning">'.$error.'</p>';
		}
		
		// But if the username/login is unique, lets finally go ahead and register them!
		else
		{
			// Encrypt the password!
			$password = md5($_POST['pass1']);
			
			// Insert into the JJTCode users database
			$query="INSERT INTO users VALUES ('$_POST[login]', '$_POST[username]', '$password', '$_POST[email]', '1', NULL, NULL, NULL, NULL, NULL)";
			$result=mysql_query($query);
		
		
			// Generate a random number to turn into a MD5 hash so we can validate the user's email address
			$num=rand();
			$code=md5($num.$_POST['login']);
			
			// Insert it into the MySQL database to remember it!
			$query="INSERT INTO user_validation VALUES ('$_POST[login]', '$code')";
			mysql_query($query);
			
			// Send the user an email to see whether their address is valid with the code
			mail($_POST['email'], "
			JJTCode Registration", "Hello, and welcome to JJTCode! You now need to confirm your email address. Please copy and
			paste this link to confirm this: http://code.jjtcomputing.co.uk/?action=validate&code=".$code
			, "From: webmaster@jjtcomputing.co.uk");
			
			echo '<p>Thank you for registering at JJTCode. Until you validate your email address, you will not have the 
			full privledges of a user.</p>';
		}
	}
}
}

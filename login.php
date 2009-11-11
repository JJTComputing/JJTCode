<?php
session_start();

require("sources/sql.php");
require("sources/functions.php");

// Make sure the IP is not banned
$query="SELECT * FROM ip WHERE ip = '$_SERVER[REMOTE_ADDR]'";
$result=mysql_query($query);

if (mysql_num_rows($result)>0) 
{
	// Load up the data from the MySQL database
	$row = mysql_fetch_assoc($result);
		
	// See how many times they have attempted to login
	if ($row['attempts']>=3)
	{
	    
	    // And convert it into a UNIX one
	    $time=strtotime($row['time']);
	
        // If they voided the 3 attempts limit over an hour ago, let them try again
        if ($time<time()-3600)
        {
	        mysql_query("DELETE FROM ip WHERE ip = '$_SERVER[REMOTE_ADDR]'"); 
		}
	
		else
		{
			//Update the ip time and show them an error message!
			mysql_query("UPDATE ip SET time = NULL WHERE ip = $_SERVER[REMOTE_ADDR]"); 
			die("You have exceeded the maximum amount of logins. Please wait an hour before trying again."); 
		}
	}
}
	
else
{
	// If the user does not exist in the IP table, create them!
	mysql_query("INSERT INTO ip VALUES ('$_SERVER[REMOTE_ADDR]', NULL, '0')");
	$row['attempts']=0;
	/* And while we're here, we will do a bit of cleaning up and make sure there are no redundant rows in 
	 the table.
	  
	 In order to do this, we will delete all rows that have a timestamp from over an hour ago */
	
	mysql_query("DELETE FROM ip WHERE time < ADDDATE(NOW(), INTERVAL -24HOUR");
}
	 
//End anti-brute force

// Validate!
$user=array(
user=>addslashes($_POST['user']),
pass=>addslashes($_POST['pass']),
md5=>md5($_POST['pass']), //Load the data into an array
);

// All the data validation has been done, so lets see about the username and password
$query="SELECT * FROM users WHERE login = '$user[user]'";
$result=mysql_query($query);
$sql=mysql_fetch_assoc($result);

// If the username does not exist, stop!
if (mysql_num_rows($result)==0)
{
	mysql_query("UPDATE ip SET attempts = '$row[attempts]', time=NULL WHERE ip = '$_SERVER[REMOTE_ADDR]'");
    die("The given username/password is incorrect");
}

// If the passwords do not match, stop!
if ($sql['password']!=$user['md5'])
{
	mysql_query("UPDATE ip SET attempts = '$row[attempts]', time=NULL WHERE ip = '$_SERVER[REMOTE_ADDR]'");
    die("The given username/password is incorrect");
}

// If they have got this far, everything is fine, so lets login!

// Just delete their IP from the database, no point keeping it now 
mysql_query("DELETE FROM ip WHERE ip = '$_SERVER[REMOTE_ADDR]'");

// Setup all the session variables and the cookies
$_SESSION['user']=$sql['username'];
$_SESSION['user_id']=$sql['id'];  
$_SESSION['level']=intval($sql['level']); 
setcookie("user", $sql['username'], time()+3600);

// Now we redirect them to the home page!
header("Location: /");
?>

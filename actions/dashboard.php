<?php
/* dashboard.php - JJTCode
*
* Purpose:
* Shows a logged in user all the relevant information in one glance:
* 1. Private Messages
* 2. Projects they are involved in
* 
* Receives From:
* 
* Sends to:
* 
* 
* Requires login: YES
* 
*/
if (!defined("jjtcode"))
{
	die("Hacking Attempt");
}

if (!login_check())
{
	echo '<h2>Error</h2><h3>You must be logged in to view this page!</h3>';
}
else
{
	// Now we load all the users info up out of the database
	
	// Projects using a handy SQL join to get their names, where the user is at least a moderator
	$query="SELECT * FROM projects, project_users WHERE project_users.user_id = '$_SESSION[user_id]'";
	$result=mysql_query($query);
	echo '<h2>Dashboard</h2>';
	echo '<p>Welcome to JJTCode.</p>';
	echo '<h3>

<?php
/* user_search.php - JJTCode
*
* Purpose:
* Searches through the user table in the MySQL database for the user_id with data
* sent from the message_create form via AJAX
* 
* 
* Receives From:
* 1. message_create when user searches for another user - username via GET
* 
* Sends to:
* 
* 
* Requires login: YES
* 
*/
require("../sources/sql.php");
require("../sources/functions.php");
require("../sources/jjtsql.php");
/* Since this script should only be accessed through AJAX, we can use die() 
 * rather than having to worry about a complete output
 * 
 * It also doesn't need to worry about checking for jjtcode being
 * defined as 
 */ 
/*
if (!login_check())
{
	die("You must be logged in to use this feature!");
}
*/
// Check the data they are sending us, after all it still could be dodgy
$username = preg_replace("[^a-zA-Z0-9]", "", $_GET['username']);

// Now check it into the database using LIKE
$query="SELECT * FROM users WHERE username LIKE '%$username%'";
$result=mysql_query($query);

while ($user=mysql_fetch_assoc($result))
{
	echo '<a onclick="user_set(\''.$user['username'].'\', '.$user['id'].');" href="#">'.$user['username'].'</a> ';
}
?>



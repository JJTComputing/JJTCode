<?php
/* message_view.php - JJTCode
 * 
 * Purpose:
 * Shows the message that the user has sent according to the message_id sent
 * 
 * Recevies from:
 * 1. message_inbox when user clicks on message title - message_id via GET
 * 
 * Sends to:
 * 
 * 
 * Requires login: YES
 * 
 */
// If the user is not logged in, they will have no messages to view!
if (!login_check())
{
	echo '<h2>Error</h2><h3>You must be logged in to view this page!</h3>';
}
else
{
	// Validate their message_id
	$message_id=preg_replace("[^0-9]", "", $_REQUEST['message_id']);
	
	// Check it in the MySQL database
	$query="SELECT * FROM messages WHERE message_id = '$message_id'";
	$result=mysql_query($query);
	
	// See whether the message exists
	if (mysql_num_rows($result)==0)
	{
		echo '<h2>Error</h2><h3>The message does not exist!</h3>';
	}
	else
	{
		// If the message exists, get the data from the database
		$message=mysql_fetch_assoc($result);
		
		echo '<h2>Messages</h2>';
		echo '<h3>'.$message['title'].'</h3>';
		echo '<textarea style="width:900px; height:500px;">'.$message['content'].'</textarea>';
	}
}
?>
<br /><br />
<a href="/?action=message_create&amp;message_id=<?php echo $message_id; ?>&amp;user_id_to=<?php echo $message['user_id_from']; ?>"><button class="" type="submit" name="" id=""><span><span>Reply</span></span></button></a>
<br /><br /><br />
	

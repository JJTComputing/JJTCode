<?php
/* message_delete.php - JJTCode
 *  
 * Purpose:
 * Deletes a users specified message
 * 
 * Receives From:
 * 1. message_inbox when user clicks on 'delete'
 * 
 * Sends To:
 * 1. message_inbox when message is deleted!
 * 
 * Requires Login: YES
 */
if (!defined("jjtcode"))
{
	die("Hacking Attempt");
}

if (!login_check())
{
	echo '<h2>Error</h2><h3>You must be logged in to access this page!</h3>';
}
else
{
	// Make sure the message_id is not empty!
	if (empty($_GET['message_id']))
	{
		echo '<h2>Error</h2><h3>You have not sent a message_id!</h3>';
	}
	else
	{
		// Validate the message_id!
		$message_id = preg_replace("[^0-9]", "", $_GET['message_id']);
		
		$query="DELETE FROM messages WHERE message_id = '$message_id' AND user_id_to = '$_SESSION[user_id]'";
		$result=mysql_query($query);
		
		// Redirect them!
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=message_inbox";
		</script>
		<noscript>Please click <a href="/?action=message_inbox">here</a> to continue.</noscript>
		<?php
	}
}
?>

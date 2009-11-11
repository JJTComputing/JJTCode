<?php
/* message_create.php - JJTCode
*
* Purpose:
* Displays a form for the user to enter the message which they
* wish to send. 
* 
* Receives From:
* 1. message_view if user wants to reply to a message - message_id
* 
* Sends to:
* 1. message_do if user sends message
* 
* Requires login: YES
* 
*/

if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

// Check whether they are logged in
if (!login_check())
{
	echo '<h2>Error</h2><h3>You must be logged in to view this page</h3>';
}
else
{
	// If a message_id has been sent, then the user wants to reply to a message, and so we can fill some data in for them!
	if (isset($_GET['message_id']) && isset($_GET['user_id_to']))
	{
		// Check the variables out!
		if (preg_match("[^0-9]", $_GET['message_id']))
		{
			echo '<h2>Error</h2><h3>The message_id is invalid!</h3>';
		}
		else
		{
			// We can let it near the SQL database, so lets see whether it exists!
			$query="SELECT * FROM messages WHERE message_id = '$_GET[message_id]'";
			$result=mysql_query($query);
			
			// If there is not results returned, then the message_id does not exist!
			if (mysql_num_rows($result)==0)
			{
				echo '<h2>Error</h2><h3>The message_id does not exist!</h3>';
			}
			else
			{
				// Its all ok, load the message info into an array!
				$message=mysql_fetch_assoc($result);
				
				// Now we construct the text we want for the textbox in this form:
				/*
				 * New message
				 * 
				 * --------------------------------------
				 * 
				 * Old Message
				 * 
				 */
				$message['content']="\n\n\n\n---------------------------------------------------------------\n".$message['content'];
			}
		}
	}
	?>
	<script src="/ajax/ajax.js" type="text/javascript"></script>
	<script src="/ajax/checkusername.js" type="text/javascript"></script>
	<form id="jNice" method="POST" action="/" name="message">
	<fieldset>
	<?php
	// We don't need to use the user_to if we are replying to a message, so we don't show it if we are!
	if (!isset($_GET['user_id_to']))
	{
		echo '<label>User To: </label><input type="text" name="user_to" class="text-long" value="" oninput="check_username(document.message.user_to.value);" /><br /><br /><br />';
		echo '<div id="messages"></div><br /><br />';
	}
	?>
	<label>Title: </label><input type="text" name="title" class="text-long" /> <br /><br /><br />
	<textarea id="message" name="content" style="width:850px; height:500px;"><?php echo $message['content']; ?></textarea><br /><br /><br />
	<input type="submit" value="Send" />
	<input type="hidden" name="action" value="message_do" />
	<input type="hidden" name="do" value="create" />
	<input type="hidden" name="user_id_to" value="<?php echo $_GET['user_id_to']; ?>" />
	</fieldset>
	</form>
	<?php
}
?>

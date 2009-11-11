<?php
/*
 * message_do.php - JJTCode
 * 
 * Purpose:
 * To send/delete messages
 * 
 * Receives From:
 * 1. message_create when user submits form - user_id_to, content and do ('create') via POST
 * 2. message_inbox when user clicks 'delete' - message_id to delete and do ('delete') via GET
 * 
 * Sends to:
 * 
 * Requires Login: YES
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
	// See what they want to do
	if ($_REQUEST['do']=="create")
	{
		// Validate and load the variables
		$error="";
		
		// User_id
		if (empty($_POST['user_id_to']))
		{
			$error.="The user_id is empty!";
		}
		
		// If it has more than numbers in it, it isn't valid!
		if (preg_match("[^0-9]", $_POST['user_id_to']))
		{
			$error.="The user_id is invalid!";
		}
		
		// Title
		if (empty($_POST['title']))
		{
			$error.="The title is empty!";
		}
		
		// Content
		if (empty($_POST['content']))
		{
			$error.="The content is empty!";
		}
		
		// If the $error variable is set, something has gone wrong, and we don't continue!
		if (!empty($error))
		{
			echo '<p class="error">'.$error.'</p>';
		}
		else
		{
			// Since the variables are plausably valid, we can do some SQL checks now
			// Checking whether the user we are sending this to is a good start
			$query="SELECT * FROM users WHERE id = '$_POST[user_id_to]'";
			$result=mysql_query($query);
			
			// If the mysql_num_rows result is 0, then the user does not exist and we don't continue!
			if (mysql_num_rows($result)==0)
			{
				echo '<h2>Error</h2><h3>The user_id you are trying to send the message to does not exist!</h3>';
			}
			else
			{
				// Now that the user_id is fine, we just addslashes and HTML encode the rest of them as there isn't much else you can do
				$content=htmlentities(addslashes($_POST['content']));
				$title=htmlentities(addslashes($_POST['title']));
				
				// Use the message_send function to finish!
				message_send($_POST['user_id_to'], $_SESSION['user_id'], $title, $content);
				
				// We're done, so redirect them to the inbox!
				?>
				<script language="javascript" type="text/javascript">
				window.location = "/?action=message_inbox";
				</script>
				<noscript>Please click <a href="/?action=message_inbox">here</a> to continue</noscript>
				<?php
			}
		}
	}
}
?>

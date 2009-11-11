<?php
/* message_inbox.php - JJTCode
 * 
 * Purpose:
 * Shows the user all their messages
 * 
 * Recevies from:
 * 
 * Sends to:
 * 1. user_profile when user clicks on username of person who sent the message - sends the user_id  
 * 
 * Requires login: YES
 * 
 */

if (!login_check)
{
	echo '<h2>Error</h2><h3>You must be logged in to access this page</h3>';
}
else
{
	// Load up their messages from the database in order of time sent
	$query="SELECT messages.title, messages.status,  users.username, messages.message_id, messages.user_id_from, messages.time_sent FROM messages, users WHERE messages.user_id_to = '$_SESSION[user_id]' AND users.id = messages.user_id_from ORDER BY time_sent DESC";
	$result=mysql_query($query);
	
	echo '<h2>Messages</h2>';
	
	// If they have no messages, tell them!
	if (mysql_num_rows($result)==0)
	{
		echo '<h3>Sorry you have no messages</h3>';
	}
	else
	{	
		// Start the table
		echo '<table border="1">';
		echo '<tr>
			<th id="from">From</th>
			<th id="title">Title</th>
			<th id="time">Time Sent</th>
			</tr>';
		// Show the users messages
		while ($message=mysql_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td class="action"><a href="/?action=user_profile&amp;user_id='.$message['user_id_from'].'" class="delete">'.$message['username'].'</a></td>';
			echo '<td class="action"><a href="/?action=message_view&amp;message_id='.$message['message_id'].'" class="view">'.$message['title'].'</a></td>';
			echo '<td class="action"><a href="">'.date("r", $message['time_sent']).'</a></td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}
}
?>
<br />
<a href="/?action=message_create"><button class="" type="submit" name="" id=""><span><span>Create Message</span></span></button></a>
			
		

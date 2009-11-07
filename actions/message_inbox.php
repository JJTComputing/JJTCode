<?php
// Check whether the user is logged in, if not this script isn't much use
if (!login_check)
{
	echo '<h2>Error</h2><h3>You must be logged in to access this page</h3>';
}
else
{
	// Load up their messages from the database in order of time sent
	$query="SELECT messages.title, messages.status,  users.username FROM messages, users WHERE messages.user_id_to = '$_SESSION[user_id]' AND users.id = messages.user_id_from ORDER BY time_sent DESC";
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
			<th>From</th>
			<th>Title</th>
			<th>Status</th>
			</tr>';
		// Show the users messages
		while ($message=mysql_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td>'.$message['username'].'</td>';
			echo '<td>'.$message['title'].'</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}
}
			
		

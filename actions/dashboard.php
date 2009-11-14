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
	$query="SELECT projects.id, projects.project_name, projects.last_modified FROM projects, project_users WHERE project_users.user_id = '$_SESSION[user_id]' AND projects.id = project_users.project_id AND project_users.level > 2";
	$result=mysql_query($query);
	
	// Show the title
	?>
	
	<h2>Dashboard</h2>
	<p>Welcome to JJTCode.</p>
	<h3>Projects</h3>
	
	<?php
	// Check how many projects the user is involved in
	if (mysql_num_rows($result)>0)
	{
		// If the user is involved in some, we can show the table!
		?>
		
		<form action="" class="jNice">
		<table border="1">
			<tr>
				<th>Project Name</th>
				<th>Last Modified</th>
			</tr>
		<?php
		while ($project=mysql_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td class="action"><a href="/?action=project_view&amp;project_id='.$project['id'].'" class="view">'.$project['project_name'].'</a></td>';
			echo '<td class="action"><a href="/?action=file_list&amp;project_id='.$project['id'].'" class="edit">'.date("r", $project['last_modified']).'</a></td>';
			echo '</tr>';
		}
		echo '</table>';
		
	}
	else
	{
		// If they aren't involved in any projects, show an error message, because otherwise the table will look ugly
		echo '<p>You are not involved in any projects!</p>';
	}
	
	
	// Now for the private messages
	$query="SELECT users.username, messages.title, messages.time_sent, messages.message_id FROM messages, users WHERE messages.user_id_to = '$_SESSION[user_id]' AND users.id = messages.user_id_from ORDER BY time_sent DESC LIMIT 0, 4";
	$result=mysql_query($query);
	?>
	
	<br />
	<h3>Messages</h3>
	<p><a href="/?action=message_inbox">Inbox</a></p>
	<br />
	
	<?php
	// See how many messages the user has
	if (mysql_num_rows($result)>0)
	{
		// Since they have some, we can show the table!
		?>
		<table border="1">
		<tr>
			<th id="from">From</th>
			<th id="title">Title</th>
			<th id="time">Time Sent</th>
		</tr>
		
		<?php
		while ($message=mysql_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td class="action">'.$message['username'].'</td>';
			echo '<td class="action"><a href="/?action=message_view&amp;message_id='.$message['message_id'].'" class="view">'.$message['title'].'</a></td>';
			echo '<td class="action">'.date("r", $message['time_sent']).'</td>';
			echo '</tr>';
		}
		
		echo '</table>';
	}
	else
	{
		// If they do not have any messages, show an error message because otherwise the table looks ugly
		echo '<p>You have no messages!</p>';
	}
}

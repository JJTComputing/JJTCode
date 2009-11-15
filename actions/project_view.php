<?php
if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

// Validate the project_id!
$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);

// See whether the user is banned
$level=get_project_level($_SESSION['user_id'], $project_id, false);

if ($level==0)
{
	echo '<h2>Error</h2>';
	echo '<h3>You are not allowed to view this project!</h3>';
}

else
{

	// The object of this page is to show the user some info about the project, so we need to load the information about
	// the project from the database
	$query="SELECT * FROM projects WHERE id='$project_id'";
	$result=mysql_query($query);
	$project=mysql_fetch_assoc($result);
	
	// Show the project name in the title!
	echo '<h2>'.$project['project_name'].'</h2>';
	
	// Show the description
	echo '<p>'.$project['description'].'</p>';
	
	echo '<br />';
	
	// Number of files
	$query="SELECT * FROM files WHERE project_id='$project_id'";
	$result=mysql_query($query);
	echo '<p>Number of Files in Project: '.mysql_num_rows($result).'</p>';
	
	// Created On
	echo '<p>Created on: '.date("r", $project['time_created']).'</p>';
	
	// Last Modified
	echo '<p>Last Modified on: '.date("r", $project['last_modified']).'</p>';
	
	// Find out the project administrators and moderators
	$query="SELECT users.username, project_users.level, project_users.user_id FROM project_users, users WHERE project_users.project_id = '$project_id' AND project_users.level > 3 AND users.id=project_users.user_id ORDER BY project_users.level ASC";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	// Right now we show the users the people involved in this project
	
	// Firstly the moderators: so loop around the array: its ordered by level by the database so level 4 will come first
	echo '<h3>Moderators</h3>';
	$user=mysql_fetch_assoc($result);
	for ($i=0; $i<$num && $user['level']==4; $i++) 
	{
		echo '<a href="/?action=user_profile&amp;user_id='.$user['user_id'].'">'.$user['username'].'</a>';
		echo '<br />';
		$user=mysql_fetch_assoc($result);
	}
	echo '<br />';
	
	// Now the full blown admins
	echo '<h3>Administrators</h3>';
	for ($i=0; $i<$num && $user['level']==4; $i++)
	{
		echo '<a href="/?action=user_profile&amp;user_id='.$user['user_id'].'">'.$user['username'].'</a>';
		echo '<br />';
		$user=mysql_fetch_assoc($result);
	}
	echo '<br />';
	
	echo '<br /><form name="jump1" class="jNice">
	 <select name="myjumpbox" OnChange="location.href=jump1.myjumpbox.options[selectedIndex].value">';
		  
	echo '<option selected>Please Select...</option>';
	echo '<option value="/?action=file_list&amp;project_id='.$project_id.'">View Files</option>';

	// If they are admins of the project, allow them to edit the project details and to delete the project!
	if ($level==4)
	{
		echo '<option value="/?action=project_edit&amp;project_id='.$project_id.'">Edit Project Details</option>';
		echo '<option value="/?action=project_delete&amp;project_id='.$project_id.'">Delete Project. Deleting a project is permanent and your files will be unrecoverable!</option>';
	}
	echo '</select>';
}
	

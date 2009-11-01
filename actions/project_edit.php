<?php
if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

// Validate the project_id!
$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);

// See whether the user is an admin
$level=get_project_level($_SESSION['user_id'], $project_id, false);

if ($level!=4)
{
	echo '<h2>Error</h2>';
	echo '<h3>You are not allowed to edit this project!</h3>';
}

else
{

	// The object of this page is to show the user some info about the project, so we need to load the information about
	// the project from the database
	$query="SELECT * FROM projects WHERE id='$project_id'";
	$result=mysql_query($query);
	$project=mysql_fetch_assoc($result);
	
	echo '<form action="/" class="jNice" method="POST">';
	echo '<fieldset>';
	echo '<p>Project Name: <input type="text" name="project_name" value="'.$project['project_name'].'" class="text-long" /></p>';
	echo '<p>Project Description: <textarea name="description">'.$project['description'].'</textarea></p>';
	echo '<p><input type="submit" value="Edit" /></p>';
	echo '</fieldset>';
	echo '<input type="hidden" name="action" value="project_do" />';
	echo '<input type="hidden" name="do" value="edit" />';
	echo '<input type="hidden" name="project_id" value="'.$_GET['project_id'].'" />';
	echo '</form>';
}
?>

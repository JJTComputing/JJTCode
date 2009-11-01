<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate the project id
$project_id = trim($_REQUEST['project_id']);
$project_id = preg_replace("/[^0-9]/", "", $project_id);

// Get their level
$level = get_project_level($_SESSION['user_id'], $project_id, false);

// Check to see whether they are an admin
if ($level<=4)
{
	?>
	<h3>Sorry you are not allowed to delete this project!</h3>
	<?php
}

else
{
	// Delete the project from the database
	$query="DELETE FROM projects WHERE id = '$project_id'";
	mysql_query($query);
	
	// Delete the directories from the database
	$query="DELETE FROM directories WHERE project_id = '$project_id'";
	mysql_query($query);
	
	// Delete all the files from the database
	$query="DELETE FROM files WHERE project_id = '$project_id'";
	mysql_query($query);
	
	// Delete all the custom levels from the database
	$query="DELETE FROM project_users WHERE project_id = '$project_id'";
	mysql_query($query);
	
	// Redirect them to the main project page
	?>
	<script language="javascript" type="text/javascript">
	window.location = "/?action=project_list";
	</script>
	<noscript>Please click <a href="/?action=project_list">here</a> to continue</noscript>
	<?php
}
?>

<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate!
$file_id = trim($_REQUEST['file_id']);
$file_id = preg_replace("[^0-9]", "", $file_id);

// Get the project_id so the last_modified can be updated
$query="SELECT project_id FROM files WHERE file_id = '$file_id'";
$result=mysql_query($query);
$project_id=mysql_result($result, 0, "project_id");

// See whether they are allowed to delete a file!
$level=get_project_level($project_id, $_SESSION['user_id'], false);

if ($level<3)
{
	echo '<h2>Error</h2><h3>You are not allowed to delete files from this project!</h3>';
}
else
{
	// Delete the file
	$query="DELETE FROM files WHERE file_id = '$file_id'";
	mysql_query($query);

	// Update the 'last_modified'
	$query="UPDATE projects SET last_modified = NULL WHERE id = '$project_id'";
	mysql_query($query);
	// End insert data
	?>
	<script language="javascript" type="text/javascript">
	window.location = "/?action=project_list";
	</script>
	<noscript>Please click <a href="/?action=project_list">here</a> to continue</noscript>
	<?php
}
?>

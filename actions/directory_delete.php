<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate the directory_id
$directory_id = preg_replace("[^0-9]", "", $_REQUEST['directory_id']);

// Load the project_id from the directory_id and database
$query="SELECT * FROM directories WHERE directory_id = '$directory_id'";
$result=mysql_query($query);
$project_id=mysql_result($result, 0, "project_id");

// Check to see whether they are authorised to view the project
$level = get_project_level($project_id, $_SESSION['user_id'], false);

if ($level < 3)
{
	echo '<h3>You are not allowed to edit this project</h3>';
}

else
{
	// Delete all the files inside the project
	$query="DELETE FROM files WHERE directory_id = '$directory_id'";
	mysql_query($query);

	// Delete the directory
	$query="DELETE FROM directories WHERE directory_id = '$directory_id' OR parent_id = '$directory_id'";
	mysql_query($query);
	
	// Redirect the user!
	?>
	<script language="javascript" type="text/javascript">
	window.location = "/?action=file_list&project_id=<?php echo $project_id; ?>";
	</script>
	<?php
}
?>

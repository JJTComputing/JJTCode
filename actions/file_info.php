<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

$level = get_project_level($_SESSION['user_id'], $_REQUEST['project_id']);

if ($level<=0)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h3>Sorry you are not allowed to edit this project!</h3>
	<?php
}

else
{
	// Validate!
	$file_id = trim($_REQUEST['file_id']);
	$file_id = preg_replace("[^0-9]", "", $file_id);

	// Load all the file information from the MySQL database
	$query = "SELECT * FROM files WHERE file_id = '".$_REQUEST['file_id']."'";
	$result = mysql_query($query);
	$file = mysql_fetch_assoc($result);
	?>
	<h3>File Information</h3>
	<p><b>Filename:</b> <?php echo $file['filename'].'.'.$file['extension']; ?></p>
	<p><b>Last Modified:</b> <?php echo date("r", strtotime($file['last_modified'])); ?></p>
	<p><b>Project ID:</b> <?php echo $file['project_id']; ?></p>
	<p><b>File ID:</b> <?php echo $file['file_id']; ?></p>
	<?php
}
?>

<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate the file_id
$file_id = trim($_REQUEST['file_id']);
$file_id = preg_replace("[^0-9]", "", $file_id);

// Load all the file information from the MySQL database
$query = "SELECT * FROM files WHERE file_id = '$file_id'";
$result = mysql_query($query);
$file = mysql_fetch_assoc($result);
$level = get_project_level($_SESSION['user_id'], $file['project_id']);

if ($level<=2)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h2>Error</h2>
	<h3>Sorry you are not allowed to edit this project!</h3>
	<?php
}
// If the file is a binary, we can't edit it, so don't let them!
elseif ($file['binary']==1)
{
	?>
	<h2>Error</h2>
	<h3>This is a binary file and cannot be viewed!</h3>
	<?php
}
else
{
	// Is the user authorised to view this?
	?>

	<script language="javascript" type="text/javascript" src="/edit_area/edit_area_full.js"></script>
	<script language="javascript" type="text/javascript">
		editAreaLoader.init({
		id : "textarea"		// textarea id
		,syntax: "<?php echo $file['extension']; ?>"			// syntax to be uses for highgliting
		,start_highlight: true	
			// to display with highlight mode on start-up
	}); 
	</script>
	<form class="jNice" action="/" method="POST">
	<h3><?php echo $file['filename'].'.'.$file['extension'].'</h3>'; ?>
	<textarea style="width:900px; height:600px;  text-indent:0px;" id="textarea" name="content">
	<?php echo $file['content']; ?>
	</textarea>
	<input type="submit" value="Edit" />
	<input type="hidden" name="action" value="file_do" />
	<input type="hidden" name="do" value="edit" />
	<input type="hidden" name="file_id" value="<?php echo $file['file_id']; ?>" />
	</form>
	<?php
}
?>

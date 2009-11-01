<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Get the variables and validate them!
$user_id_edited = preg_replace("[^0-9]", "", $_REQUEST['user_id']);
$user_id_editing = $_SESSION['user_id'];
$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);

// Make sure they are an admin!
$level=get_project_level($user_id_editing, $project_id, false);

if ($level!=4)
{
	echo '<h3>You are not permitted to edit the permissions for this project!</h3>';
}
else
{
	// If they are an admin, lets go!
	
	// Validate the requested level
	$level=preg_replace("[^0-5]", "", $_REQUEST['level']);
	
	/* Now we need to do some checking about changing the level.
	 * 
	 * Firstly check whether they are actually changing the level! */
	$old_level=get_project_level($user_id_edited, $project_id, false);
	if ($old_level!=$level)
	{
		$query="SELECT default_level FROM projects WHERE id = '$project_id'";
		$result=mysql_query($query);
		$default_level=mysql_result($result, 0, "default_level");
	
		// If the default level is the same as the level they want to change it to, we delete their custom level!
		if ($default_level==$level)
		{
			$query="DELETE FROM project_users WHERE user_id = '$user_id_edited' AND project_id = '$project_id'";
			mysql_query($query);
		}
		/* If the old level is the same as the default level, we have to create a new row because this is the first time
		   the user has had a custom level for this project */
		elseif ($old_level==$default_level)
		{
			$query="INSERT INTO project_users VALUES ('$user_id_edited', '$project_id', '$level')";
			mysql_query($query);
		}
		/* Well if they have got this far without matching an if clause, they have had a custom level for this project before,
		 * and so we just update that! */
		else
		{
			$query="UPDATE project_users SET level = '$level' WHERE user_id = '$user_id_edited' AND project_id = '$project_id'";
			mysql_query($query);
		}
		
		// Redirect the user
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=file_view&project_id=<?php echo $project_id; ?>";
		</script>
		<?php
	}
}
?>
	
<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// See what the user wants to do
if ($_REQUEST['do']=="create")
{
	// Validate the variables
	
	// Check whether any are empty
	if (!empty($_REQUEST['project_id']))
	{
		$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);
	}
	else
	{
		$error.="The project_id field is empty! ";
	}
	
	if (!empty($_REQUEST['directory']))
	{
		$directory = preg_replace("[^0-9a-zA-Z]", "", $_REQUEST['directory']);
	}
	else
	{
		$error.="The directory field is empty! ";
	}
	
	if (!empty($_REQUEST['parent']))
	{
		$directory_id = preg_replace("[^0-9]", "", $_REQUEST['parent']);
	}
	else
	{
		$error.="The directory parent field is empty! ";
	}
	
	// Check whether the project actually exists
	$query="SELECT * FROM projects WHERE id = '$project_id'";
	$result=mysql_query($query);
	
	if (mysql_num_rows($result)==0)
	{
		$error.="The project does not exist!";
	}
	
	// Now check whether the error variable has been set, and if so, an error has occured and we show them the error and don't
	// proceed!
	if (isset($error))
	{
		echo $error;
	}
	else
	{
		// End validation
	
		// If the directory_id is / than we set the value to NULL
		if ($directory_id=="/")
		{
			$query="INSERT INTO directories VALUES ('$directory', NULL, '$project_id', NULL)";
		}
	
		else
		{
			$query="INSERT INTO directories VALUES ('$directory', '$directory_id', '$project_id', NULL)";
		}
	
		mysql_query($query);
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=file_list&project_id=<?php echo $project_id; ?>";
		</script>
		<?php
	}
} 
?>

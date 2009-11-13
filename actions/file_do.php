<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

if (!isset($_REQUEST['project_id']))
{
	// Validate the file_id!
	$file_id = trim($_REQUEST['file_id']);
	$file_id = preg_replace("[^0-9]", "", $file_id);

	// Get the project id using the file id
	$query="SELECT project_id FROM files WHERE file_id = '$_REQUEST[file_id]'";
	$result=mysql_query($query);
	$project_id=mysql_result($result, 0, "project_id");
}

else
{
	// Get the project_id and validate it while we're here!
	$project_id = trim($_REQUEST['project_id']);
	$project_id = preg_replace("/[^a-zA-Z0-9]/", "", $project_id);
}

// Its generally used for something
$time=time();

// Load up the level!
$level = get_project_level($_SESSION['user_id'], $project_id, false);

if ($level<=2)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h2>Error</h2>
	<h3>Sorry you are not allowed to edit this project!</h3>
	<?php
}

else
{
	// Lets see what the user wants to do
	if ($_REQUEST['do']=="create")
	{
		// Validate and load the variables
		
		// Filetype
		if (!empty($_POST['filetype']))
		{
			$filetype=preg_replace("/[^a-zA-Z0-9\s]/", "", trim($_POST['filetype']));
		}
		else
		{
			$error.="The Filetype is empty! ";
		}
		
		// Filename
		if (!empty($_POST['filename']))
		{
			$filename=preg_replace("/[^a-zA-Z0-9]/", "", trim($_POST['filename']));
		}
		else
		{
			$error.="The Filename is empty! ";
		}
		
		// Content
		if (!empty($_POST['content']))
		{
			$content=$_POST['content'];
		}
		else
		{
			$error.="There is no content! ";
		}
		
		// If the directory = /, check it to NULL
		if (!empty($_POST['directory']))
		{
			$directory='\''.preg_replace("/[^0-9]/", "", $_POST['directory']).'\'';
		}
		else
		{
			$directory="NULL";
		}
		
		// Check whether the project actually exists
		$query="SELECT * FROM projects WHERE id = '$project_id'";
		$result=mysql_query($query);
		
		if (mysql_num_rows($result)==0)
		{
			$error.="The project you wish to insert the file in does not exist! ";
		}
		
		// If the error has been set, then an error has occured so we don't go any futher!
		if (isset($error))
		{
			echo $error;
		}
		// But if it isn't, to the insert!
		else
		{
			// Get the time for the last modified
			$time=time();
			
			// If the binary variable is not set, then the file is not a binary, but the database needs to know either
			// way
			if (!isset($binary))
			{
				$binary=0;
			}
		
			// Insert data into MySQL
			$query="INSERT INTO files VALUES ('$filename', '$filetype', '$content', NULL, '$project_id', $directory, $binary, NULL)";
			$result=mysql_query($query);
			echo mysql_error();
			
			// Update the 'last_modified'
			$query="UPDATE projects SET last_modified = '$time' WHERE id = '$project_id'";
			mysql_query($query);
			// End insert data
	
			// Get the file_id from the MySQL database for the redirect
			$query="SELECT file_id FROM files WHERE filename = '$filename' AND extension='$filetype' AND project_id='$project_id'";
			$result=mysql_query($query);
			echo mysql_error();
			$file_id=mysql_result($result, 0, "file_id");
			
			// Redirect the user to the file edit!
			?>
			<script language="javascript" type="text/javascript">
			window.location = "/?action=file_edit&file_id=<?php echo $file_id; ?>";
			</script>
			<noscript>Please click <a href="/?action=file_edit&file_id=<?php echo $file_id; ?>">here</a> to continue</noscript>
			<?php
		}
	}
	
	elseif ($_REQUEST['do']=="edit")
	{
		// Load the variables
		$content=$_POST['content'];
		$time=time();
		// End validation
	
		// Insert data into MySQL
		$query="UPDATE files SET content='$content', last_modified=NULL WHERE file_id='$file_id'";
		$result=mysql_query($query);
		echo mysql_error();
		// End insert data
	
		// Update the 'last_modified'
		$query="SELECT project_id FROM files WHERE file_id='$file_id'";
		$result=mysql_query($query);
		$project_id=mysql_result($result, 0, "project_id");
	
		$query="UPDATE projects SET last_modified = '$time' WHERE id = '$project_id'";
		mysql_query($query);
		// End insert data
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=file_edit&file_id=<?php echo $file_id; ?>";
		</script>
		<noscript>Please click <a href="/?action=file_edit&amp;file_id=<?php echo $file_id; ?>">here</a> to continue.</noscript>
		<?php
	}
}

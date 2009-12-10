<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Load up the objects
require("classes/projects.php");
require("classes/files.php");

if (!isset($_REQUEST['project_id']))
{
	// Validate the file_id!
	$file_id = preg_replace("[^0-9]", "", $_POST['file_id']);

	// Create the file object using the file_id, and then create the project object using
	// the project_id from the file object
	$file = new file($file_id);
	$project = new project($file->info['project_id']);
	
	// Save some memory
	unset($file_id);
}

else
{
	// Get the project_id, validate it, and create the object
	$project_id = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['project_id']);
	$project = new project($project_id);
	
	// Save some memory
	unset($project_id);
}

// Its generally used for something
$time=time();

// Load up the level!
$level = $project->get_level($user->id);

if ($level<=2)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h2>Error</h2>
	<h3>You are not allowed to edit this project!</h3>
	<?php
}
// Check whether the project exists
elseif (!$project)
{
	?>
	<h2>Error</h2>
	<h3>The project does not exist!</h3>
	<?php
}
// If the file object is set, check whether the file exists
elseif (isset($file) && !$file)
{
	?>
	<h2>Error</h2>
	<h3>The file does not exist!</h3>
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
		
		// If the error has been set, then an error has occured so we don't go any futher!
		if (isset($error))
		{
			echo $error;
		}
		// But if it isn't, to the insert!
		else
		{	
			// If the binary variable is not set, then the file is not a binary, but the database needs to know either
			// way
			if (!isset($binary))
			{
				$binary=0;
			}
	
			// Put into database using class function
			$sucess=$project->create_file($filename, $extension, $content, $directory, $binary);
	
			// Check whether everything went well
			if (!$sucess)
			{
				echo '<h2>Error</h2>';
				echo '<h3>An error has occured. Your file may not have been created properly.</h3>';
			}
			else
			{
				// Redirect the user to the list of the project files
				?>
				<script language="javascript" type="text/javascript">
				window.location = "/?action=file_list&project_id=<?php echo $project->id; ?>";
				</script>
				<noscript>Please click <a href="/?action=file_list&project_id=<?php echo $project->id; ?>">here</a> to continue</noscript>
				<?php
			}
		}
	}
	
	elseif ($_REQUEST['do']=="edit")
	{
		// Load the variables
		$content=addslashes($_POST['content']);
		
		// End validation
	
		// Insert data into MySQL
		$file->edit_file($content);
	
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=file_edit&file_id=<?php echo $file->id; ?>";
		</script>
		<noscript>Please click <a href="/?action=file_edit&amp;file_id=<?php echo $file->id; ?>">here</a> to continue.</noscript>
		<?php
	}
}

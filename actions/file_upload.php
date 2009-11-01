<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}
// Validate the project_id
$project_id=preg_replace("[^0-9]", "", $_REQUEST['project_id']);

// Load up the level!
$level = get_project_level($_SESSION['user_id'], $project_id, false);

if ($level<=2)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h3>Sorry you are not allowed to edit this project!</h3>
	<?php
}

else
{

	// Have a look whether they've uploaded the file
	if (!isset($_FILES['textfile']))
	{
		?>
		<h2>Upload File</h2>
		<form enctype="multipart/form-data" action="/" method="POST" class="jNice" name="post">
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
		<label>File: </label> <input name="textfile" type="file"> <br />
		<input type="hidden" name="action" value="file_upload">
		<input type="hidden" name="project_id" value="<?php echo $_REQUEST['project_id']; ?>" />
		<input type="hidden" name="do" value="create" />
		<br /><br />
			 <?php
	// Give them an offer to create the file in a directory, if there are any
	$directories = jjtsql_field_array_load("directories", "project_id", $project_id);
	// If it is a bool, there are no directories, and so the foreach loop will generate an error
	if (!is_bool($directories))
	{
		echo '<label>Directory:</label> <select name="directory">';
		echo '<option value="">/</option>';
		foreach ($directories as $value)
		{
			echo '<option value='.$value['directory_id'].'>'.$value['directory'].'</option>';
		}
	}
	?>
	</select>
	<br /><br /><br /><br />
		<input type="submit" value="Upload File">
		</form>
		
		<?php
	}
		// Check whether there has been an error
	elseif ($_FILES['textfile']['error'] > 0)
	{ // There's been an error, lets have a look at what it is
		echo 'Problem: ';
		switch ($_FILES['textfile']['error'])
		{
			case 1:	echo 'File exceeded upload_max_filesize';
			break;
			
			case 2:	echo 'File exceeded max_file_size';
			break;
			
			case 3:	echo 'File only partially uploaded';
	  		break;
      
			case 4:	echo 'No file uploaded';
	  		break;
	  
			case 6:   echo 'Cannot upload file: No temp directory specified.';
	  		break;
	  
			case 7:   echo 'Upload failed: Cannot write to disk.';
	  		break;
		}
	}
	// But if it has been, lets do the work!
	else
	{
		// Firstly, we need to open it
		$file=fopen($_FILES['textfile']['tmp_name'], "r");
		
		$info=mime_content_type($_FILES['textfile']['tmp_name']);
		
		// Load up the content array and then we'll use the file_do.php file to do the rest
		// Simulate POST variables

		$_POST['content']=addslashes(fread($file, filesize($_FILES['textfile']['tmp_name'])));
		
		// Load the filename and filetype
		list($_POST['filename'], $_POST['filetype']) = explode(".", $_FILES['textfile']['name']);
		
		// Check if its a binary file, because then the database needs to know
		if (!strstr($info, "text"))
		{
			$binary=1;
		}
		
		// Use the file_do file to put it into the database 
		require("file_do.php");
		}	
	}
?>

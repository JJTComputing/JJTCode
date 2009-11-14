<?php
 /* archive_download.php - JJTCode
  * 
  * Purpose:
  * To package all of the files in an archive and send the archive to the user
  * 
  * Receives From:
  * 
  * Sends To:
  * 
  * Requires Login: NO
  * 
  * Depending on the projects default level, a login might be required
  * 
  */ 

require("sources/sql.php");
require("sources/functions.php");
require("sources/jjtsql.php");

// Validate the project id, because it is used a lot for MySQL
$project_id = trim($_REQUEST['project_id']);
$project_id = preg_replace("[^0-9]", "", $project_id);

// Get the level of the user, but since we've already validated the project_id we don't need the function to do it again!
$level = get_project_level($_SESSION['user_id'], $project_id, false);

if (is_bool($level))
{
	// If the project does not exist, tell them!
	?>
	<h3>The Requested project does not exist!</h3>
	<?php
}

elseif ($level<=0)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h3>Sorry you are not allowed to view this project.</h3>
	<?php
}
else
{
	// include class
	require("Archive/Tar.php");

	// create Archive_Tar() object in the tempory folder
	$tar = new Archive_Tar("/tmp/".$project_id.".tar");

	// Send the headers!
	header("Content-Type: application/x-tar");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=".$project_id.".tar");

	/////////////////////////////////////////////////////////////////////////////////////
	// Now we need to set up the file list, so we load the files from the MySQL database
	
	// Firstly the files with no directory
	$query="SELECT * FROM files WHERE directory_id IS NULL AND project_id = '$project_id'";
	$result=mysql_query($query);
	
	// For the array
	$i=0;
	
	// Start up the loop to create the files
	while ($fileinfo=mysql_fetch_assoc($result))
	{
		// Build the filename
		$filename="/tmp/".$fileinfo['filename'].".".$fileinfo['extension'];
		
		// Create the file and put the information into it!
		$file = fopen($filename, "w");
		
		fwrite($file, $fileinfo['content']);
		
		// Add the file to the array which will be loaded into the archive
		$filearray[$i] = $filename;
		
		fclose($file);
		// For the array
		$i++;
	}
	
	// Now for the directories (and files within them) in the project
	// This requires a 2nd SQL query due to the nature of creating directories in the archive, it'll get messed up if we try
	// to do it with 1 loop
	
	$query="SELECT directories.directory, files.filename, files.extension, files.content FROM files, directories WHERE files.project_id = '$project_id' AND files.directory_id IS NOT NULL AND directories.directory_id = files.directory_id";
	$result=mysql_query($query);
	
	while ($fileinfo = mysql_fetch_assoc($result))
	{
		// If the directory doesn't exist, create it!
		if (!file_exists("/tmp/".$fileinfo['directory']."/"))
		{
			mkdir("/tmp/".$fileinfo['directory']."/");
		}
		
		// Build the filename
		$filename="/tmp/".$fileinfo['directory']."/".$fileinfo['filename'].".".$fileinfo['extension'];
		
		// Create the file and put the information into it!
		$file = fopen($filename, "w");
		
		fwrite($file, $fileinfo['content']);
		
		// Add the file to the array which will be loaded into the archive
		$filearray[$i] = $filename;
		
		fclose($file);
		// For the array
		$i++;
	}
	
	// build archive
	$tar->create($filearray) or die("Could not create archive!");

	// Load the tar into memory
	$tar = fopen("/tmp/".$project_id.".tar", "r");
	
	echo fread($tar, filesize("/tmp/".$project_id.".tar"));
}
?>

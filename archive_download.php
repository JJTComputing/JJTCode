 <?php
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
	require("Tar.php");

	// create Archive_Tar() object in memory (thats what the php://memory/ does)
	$tar = new Archive_Tar("php://memory/".$project_id.".tar");

	/////////////////////////////////////////////////////////////////////////////////////
	// Now we need to set up the file list, so we load the files from the MySQL database
	
	// Firstly the files with no directory
	$query="SELECT * FROM files WHERE directory_id IS NULL";
	$result=mysql_query($query);
	
	
	// build archive
	$tar->create($files) or die("Could not create archive!");

	// remove the variable
?>

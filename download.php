<?php
require("sources/sql.php");
require("sources/functions.php");
require("sources/jjtsql.php");

session_start();
// Make sure they are authorised!

// To do this, we need to load the file information out of the MySQL database
$file_id=(int)$_GET['file_id'];

$query="SELECT * FROM files WHERE file_id = '$file_id'";
$result=mysql_query($query);
$file=mysql_fetch_assoc($result);

$level = get_project_level($_SESSION['user_id'], $file['project_id']);

if ($level<1)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h3>Sorry you are not allowed to view this project!</h3>
	<?php
}
else
{
	
	// Open up a file dialogue, and send the file title
	header("application/force-download");
	header("Content-Disposition: attachment; filename=".$file['filename'].".".$file['extension']."");

	// Create an array of known file types
	$mime_types=array(
		"pdf" => "application/pdf",
		"txt" => "text/plain",
		"html" => "text/html",
		"htm" => "text/html",
		"exe" => "application/octet-stream",
		"zip" => "application/zip",
		"doc" => "application/msword",
		"xls" => "application/vnd.ms-excel",
		"ppt" => "application/vnd.ms-powerpoint",
		"gif" => "image/gif",
		"png" => "image/png",
		"jpeg"=> "image/jpg",
		"jpg" =>  "image/jpg",
		"php" => "text/plain"
	);	
	// Send the filetype
	header("Content-type: ".$mime_types[$file['extension']]);

	// Send the file!
	echo $file['content'];
}
	?>

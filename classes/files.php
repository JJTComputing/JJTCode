<?php
/* files.php - JJTCode
 * 
 * Created on Dec 5, 2009
 * 
 * Controls the file class
 */
if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

class file extends jjtcode 
{
	// MySQL files
	public $table="files";
	public $where="id";
	
	function edit_file($content)
	{
		$time = time();
		// Do the SQL query
		$query="UPDATE files SET content='$content', last_modified = '$time' WHERE id = '$this->id'";
		$result=mysql_query($query);
		
		// Return whether it was successful
		return $result;
	}
}
?>

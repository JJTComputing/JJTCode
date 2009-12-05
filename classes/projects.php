<?php
/* projects.php - JJTCode
 * 
 * Created on Dec 3, 2009
 * 
 * Purpose:
 * To contain the classes for projects
 */
 
 if (!defined("jjtcode"))
 {
 	die("Hacking Attempt");
 }
 
 class projects
 {
 	public $id;
 	public $level;
 	public $info;
 	
 	private $exists;
 	
 	function __construct($project_id)
 	{
 		// When the object is made, get the information about the project
 		// from the MySQL database
 		$query="SELECT * FROM projects WHERE id = '$project_id'";
 		$result=mysql_query($query);
 		
 		// Check whether the project exists
 		if (!$result || mysql_num_rows($result)==0)
 		{
 			$this->exists=false;
 			return false;
 		}
 		
 		// But if it does, load the info up!
 		$info=mysql_fetch_assoc($result);
 		
 		// Set the variables in the object
 		$this->id=$project_id;
 		$this->level=$info['default_level'];
 		$this->info=$info;
 		
 		$this->exists=true;
 	}
 	
 	function get_files($directory_id="")
 	{
 		// Check if the directory id is empty, if not the user wants to load the files in a directory up!
 		if (!empty($directory_id))
		{
			$files = jjtsql_table_double_load("files", "project_id", $this->id, "directory_id", $directory_id);
		}
		// If it is empty, load the files from the root directory up
		else
		{
			$files = jjtsql_table_null_load("files", "project_id", $this->id, "directory_id");
		}
		
		return $files;
 	}
 	
 	function get_directory($directory_id)
 	{
 		// Check if the directory id is empty, if not the user wants to load the files in a directory up!
 		if (!empty($directory_id))
		{
			$directory = jjtsql_table_double_load("directories", "project_id", $this->id, "directory_id", $directory_id);
		}
		// If it is empty, load the files from the root directory up
		else
		{
			$directory = jjtsql_table_null_load("directories", "project_id", $this->id, "directory_id");
		}
		
		return $directory;
 	}
}
?>

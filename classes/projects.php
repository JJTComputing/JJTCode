<?php
/* projects.php - JJTCode
 * 
 * Created on Dec 3, 2009
 * 
 * Purpose:
 * To contain the classes for projects
 * 
 * Function:
 * __construct()
 * (This is inherited from the jjtcode class)
 * This loads up all the information about the project from the MySQL database
 * 
 */
 
if (!defined("jjtcode"))
{
	die("Hacking Attempt");
}
 
class project extends jjtcode
{
 	public $table = "projects";
 	public $where = "id";
 	
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
 	
 	function get_directory($directory_id="")
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
 	
 	function get_level($user_id)
 	{
		// Check whether this user has any special permissions for this project
		$query="SELECT * FROM project_users WHERE user_id = '$user_id' AND project_id = '$this->id'";
		$result=mysql_query($query);

		// If there are special permissions, use them!
		if (mysql_num_rows($result)>0)
		{
			$level=mysql_result($result, 0, "level");
		}
		// But if there aren't, use the standard ones
		else
		{
			echo 2;
			$level=$this->info['level'];
		}
		
		return $level;
 	}
}
?>

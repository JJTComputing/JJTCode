<?php
/* projects.php - JJTCode
 * 
 * Created on Dec 3, 2009
 * 
 * Purpose:
 * To contain the classes for projects
 * 
 * Functions:
 * bool __construct()
 * (This is inherited from the jjtcode class)
 * This loads up all the information about the project from the MySQL database. Returns false
 * if the project does not exist
 * 
 * array get_files($directory_id="")
 * Uses the JJTSQL libraries to return a list of the files in a project in the form: $files[file_id][information].
 * If you specify a directory_id, it will return a list of the files in that directory, if you do not,
 * it will return a list of the files in a project without directory (e.g. directory_id = NULL)
 * 
 * array get_directory($directory_id="")
 * Pretty much the same code as get_files. Uses the JJTSQL libraries to return a list of directories in a
 * project in the form: $directory[directory_id][information] 
 * If you specify a directory_id, it will return a list of the directories in that directory, if you do not,
 * it will return a list of the directories in a project without directory (e.g. directory_id = NULL)
 * 
 * int get_level($user_id)
 * Using the user_id it fetches the user's level for the particular project and returns it. It is the same
 * as get_project_level() 
 * 
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
 	
 	function create_file($filename, $extension, $content, $directory, $binary=0)
 	{
 		// Time
 		$time=time();
 		
 		// Insert data into MySQL
		$query="INSERT INTO files VALUES ('$filename', '$extension', '$content', '$time', '$this->id', '$directory', '$binary', NULL)";
		$result=mysql_query($query);
		
		// Update the last modified of the project
		$query="UPDATE projects SET last_modified = '$time' WHERE id = '$this->id'";
		mysql_query($query);
		
		// Tell the user how it went!
		return $result;
 	}

	function edit_info($title, $description)
	{
		// Time
		$time=time();
		
		// Update the database
		$query="UPDATE projects SET project_name = '$project', description = '$description', last_modified = '$time' WHERE id = '$project_id'";
		$result=mysql_query($query);
		
		return $result;
	}
}
?>

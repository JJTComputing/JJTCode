<?php
/* classes/users.php - JJTCode
 * 
 * Purpose:
 * To contain the classes to do with the user's info and modfying their permissions
 * 
 * Notes:
 * Due to the possibilty of guests who do not have a user_id, this class has to have its own constructor 
 * and so cannot inherit off the jjtcode class, despite sharing a large amount of code.
 * 
 * There is also some columns that don't need loading up, such as the password column 
 */
 
if (!defined("jjtcode"))
{
	die("Hacking Attempt");
}

class user
{
	public $id;
	public $info;
	
	function __construct($id)
	{
		// If the user_id is 0, the user is a guest which requires slightly different handling
		if ($id==0)
		{
			$this->id=0;
			$this->info['username']="Guest";
		}
		else
		{	
			// When the object is made, gather information about it from the MySQL DB
			// Here is where the $table and $where values come into use
			$query="SELECT * FROM users WHERE id = '$id' ";
			$result=mysql_query($query);
		
			// Make sure the query exists, and that the user has actually passed something to the function
			if (mysql_num_rows($result)==0 || empty($id))
			{
				// And if it doesn't, return false
				return false;
			}
		
			// But if it does, load up the info!
			$this->id = $id;
			$this->info = mysql_fetch_assoc($result);
		}
	}	
}
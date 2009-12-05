<?php
/* classes/users.php - JJTCode
 * 
 * Purpose:
 * To contain the classes to do with the user's info and modfying their permissions
 */
 
if (!defined("jjtcode"))
{
	die("Hacking Attempt");
}

class user 
{
	public $id;
	public $info; 
	public $level;
	
	function __construct($user_id)
	{
		// When the object is made, we gather all the user's info into the object
		// using the MySQL database
		$query="SELECT * FROM users WHERE id = '$$user_id'";
		$result=mysql_query($query);
		$info=mysql_fetch_assoc($result);
		
		// Set up the variables in the object
		$this->info=$info;
		$this->id=$user_id;
		$this->level=$info['level'];
	}
}
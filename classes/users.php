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

class user extends jjtcode
{
	public $table="users";
	public $where="id";
}
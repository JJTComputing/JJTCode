<?php
/* class.php - JJTCode
 * 
 * Created on Dec 6, 2009
 * 
 * This is a general class that most of the other objects to do with
 * JJTCode will inherit from. It is unlikely it will be used directly
 */
 
class jjtcode
{
	// Generic variables
	public $id;
	public $info;
	
	// To do with the MySQL stuff for the __construct
	public $table;
	public $where;
	
	function __construct($id)
	{
		// When the object is made, gather information about it from the MySQL DB
		// Here is where the $table and $where values come into use
		$query="SELECT * FROM $this->table WHERE $this->where = '$id' ";
		$result=mysql_query($query);
		
		// Make sure the query exists
		if (mysql_num_rows($result)==0)
		{
			// And if it doesn't, return false
			return false;
		}
		
		// But if it does, load up the info!
		$this->id = $id;
		$this->info = mysql_fetch_assoc($result);
		
		// Unset the MySQL variables: save some memory
		unset($this->table);
		unset($this->where);
		
	}
}
?>

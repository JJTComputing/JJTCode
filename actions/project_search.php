<?php
/* project_search.php - JJTCode
 * 
 * Purpose:
 * To search through all the projects looking for the string the user wants
 * 
 * Receives From:
 * 
 * Sends To:
 * 
 * Requires Login: NO
 */ 
if (!defined("jjtcode"))
{
	die("Hacking Attempt!");
}

// If their search term is blank, show an error message
if (empty($_GET['search']))
{
	echo '<h2>Error</h2><h3>You cannot have a blank search term!</h3>';
}
else
{
	// Validate their search term: only numbers and letters!
	$search = preg_replace("[^0-9A-Za-z]", "", $_GET['search']);
	
	// Have a lookie in the MySQL database for their search term
	$query="SELECT * FROM projects WHERE project_name LIKE '%$search%' OR description LIKE '%$search%' AND visible = '1'";
	$result=mysql_query($query);
	
	// Check whether we have any results: otherwise the table gets messed up if we don't
	if (mysql_num_rows($result)==0)
	{
		echo '<h2>No Results</h2><h3>No results have been found in your search!</h3>';
	}
	else
	{
		// Setup the table
		?>
		
		<table border="1">
		<tr>
			<th>Project Name</th>
			<th>Description</th>
			<th>Last Modified</th>
			<th>Profile</th>
		</tr>
	
		<?php
		// Show the results!
		
		while ($message=mysql_fetch_assoc($result))
		{
			echo '<tr>';
			echo '<td class="action"><a href="/?action=file_list&amp;project_id='.$message['id'].'" class="delete">'.$message['project_name'].'</a></td>';
			echo '<td>'.$message['description'].'</td>';
			echo '<td>'.date("r", $value['last_modified']).'</td>';
			echo '<td class="action"><a href="/?action=project_view&amp;project_id='.$message['id'].'" class="view">Profile</a></td>';
			echo '</tr>';
		}
	echo '</table>';
	}
}
?>

	
	




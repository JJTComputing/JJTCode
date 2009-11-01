<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// If they are not logged in, do not let them view the user list
if (!login_check())
{
	echo '<h2>Error</h2><h3>You need to be logged in to view the user list</h3>';
}
// If they are banned, do not show them the page
elseif ($_SESSION['level']==0)
{
	echo '<h2>Error</h2><h3>You are banned from viewing the user list!</h3>';
}

else
{

	// Load up the users info!
	$users=jjtsql_table_field_array_load("users");

	// Make the table
	?>
	<table border="1">
	<tr>
	<th>Username</th>
	<th>Permissions</th>
	</tr>
	
	<?php
	foreach ($users as $value)
	{
		echo '<tr>';
		echo '<td class="action"><a href="/?action=user_profile&amp;user_id='.$value['id'].'" class="view">'.$value['username'].'</a></td>';
		echo '<td class="action"><a href="/?action=user_permissions&amp;user_id='.$value['id'].'" clas>Permissions</a></td>';
		echo '</tr>';
	}
	?>
	</table>
<?php
}
?>

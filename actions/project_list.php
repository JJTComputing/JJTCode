<?php
/* project_list.php - JJTCode
 * 
 * Purpose:
 * To show a list of all the projects marked as 'visible' in the database for all
 * to view.
 * 
 * Receives From:
 * 
 * Sends To:
 * 1. project_view when user clicks on 'Profile' - sends the project_id via GET
 * 2. file_list when user clicks on project name - sends the project_id via GET
 * 3. project_search when user submits form
 * 
 * Requires Login: NO
 */
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}
?>
<form action="/" class="jNice" method="GET">
<table border="1">
	<tr>
		<th>Project Name</th>
		<th>Description</th>
		<th>Last Modified</th>
		<th>Profile</th>
	</tr>

<?php
// Load up the projects data from the MySQL table
$table = jjtsql_field_array_load("projects", "visible", "1");

foreach ($table as $value)
{
	echo '<tr>';
	echo '<td class="action"><a href="/?action=file_list&amp;project_id='.$value['id'].'" class="delete">'.$value['project_name'].'</a></td>';
	echo '<td>'.$value['description'].'</td>';
	echo '<td>'.date("r", $value['last_modified']).'</td>';
	echo '<td class="action"><a href="/?action=project_view&amp;project_id='.$value['id'].'" class="view">Profile</a></td>';
	echo '</tr>';
}
?>
</table>
<?php
// If they can create projects, let them!
if ($_SESSION['level']>=2)
{
	 echo '<a href="/?action=project_create"><button class="" type="submit" name="" id=""><span><span>Create Project</span></span></button></a>';
}
?>
<br /><br /><br />
<fieldset>
<label>Search: </label><input type="text" name="search" class="text-long" /><br />
<input type="hidden" name="action" value="project_search" />
<input type="submit" value="Search" />
</fieldset>
</form>
	

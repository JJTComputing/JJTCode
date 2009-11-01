<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}
?>
<form action="" class="jNice">
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
<br />
</form>
	

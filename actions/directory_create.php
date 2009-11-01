<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);
$directories = jjtsql_field_array_load("directories", "project_id", $project_id);
?>
<form class="jNice" action="/" >
<fieldset>
<label>Directory Name:</label> <input type="text" name="directory" class="text-long" />
<br /><br /><br />
<label>Parent:</label> 
<select name="parent">
<option value="/">None</option>
<?php 
if (count($directories)>0)
{
	foreach($directories as $value)
	{
		echo '<option value="'.$value['directory_id'].'">'.$value['directory'].'</option>';
	}
}
?>
</select>
<br /><br /><br />
<input type="hidden" name="action" value="directory_do" />
<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
<input type="hidden" name="do" value="create" />
<input type="submit" value="Create" />
</fieldset>
</form>

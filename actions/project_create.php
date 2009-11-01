<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Check the user is allowed to create projects
if ($_SESSION['level']<=2)
{
	echo '<h3>You are not allowed to create projects!</h3>';
}
else
{
	?>
	<form action="/" method="POST" class="jNice">
	<fieldset>
	<p><label>Project Name:</label> <input type="text" name="project_name" class="text-long" /></p>
	<p><label>Description:</label> <textarea name="description"></textarea></p>
	<p>
	<label>Default Access Level:</label>
	<select name="level">
	<option value="1">2: All Can View Files (Recommended)</option>
	<option value="0">1: Can't Access Project or Read Files</option>
	<option value="2">3: All Can Create, edit and delete files (Unsecure!)</option>
	</select>
	</p>
	<p><label>Visible by Default:</label>
	<select name="visible">
	<option value="1">Yes</option>
	<option value="0">No</option>
	</select></p>
	<input type="hidden" name="action" value="project_do" />
	<input type="hidden" name="do" value="create" />
	<input type="submit" value="Create" />
	</fieldset>
	</form>
	<?php
}
?>

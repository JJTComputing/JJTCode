<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Make sure they have permissions to create files!
$project_id = preg_replace("[^0-9]", "", $_REQUEST['project_id']);
$level = get_project_level($_SESSION['user_id'], $project_id, false);

if ($level<=2)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h3>Sorry you are not allowed to edit this project!</h3>
	<?php
}

else
{
	?>
	<script language="javascript" type="text/javascript" src="/edit_area/edit_area_full.js"></script>
	<script language="javascript" type="text/javascript">

	editAreaLoader.init({
		id : "textarea"		// textarea id
		,syntax: ""		// syntax to be uses for highlighting
		,start_highlight: false		// to display with highlight mode on start-up
	});
	</script>

	<form method="POST" action="/" name="filetype" class="jNice">
	<fieldset>
	Filename: <input type="text" name="filename" class="text-long" /> 
	<input type="text" name="filetype" class="text-small" /><!--<select name="filetype">
	<option value="html">HTML</option>
	<option value="css">CSS</option>
	<option value="javascript">Javascript</option>
	<option value="xml">XML</option>
	<option value="php">PHP</option>
	<option value="sql">SQL</option>
	<option value="python">Python</option>
	<option value="vb">Visual Basic</option>
	<option value="c">C</option>
	<option value="cpp">C++</option>
	<option value="pascal">Pascal</option>
	<option value="brainfuck">Brainfuck</option>
	</select> -->

	<br />

	<textarea id="textarea" style="width:900px; height:600px;" name="content"></textarea>

	<br />

	<input type="hidden" name="action" value="file_do" />
	<input type="hidden" name="do" value="create" />
	<input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
	 <?php
	// Give them an offer to create the file in a directory, if there are any
	$directories = jjtsql_field_array_load("directories", "project_id", $project_id);
	// If it is a bool, there are no directories, and so the foreach loop will generate an error
	if (!is_bool($directories))
	{
		echo 'Directory: <select name="directory">';
		echo '<option value="">/</option>';
		foreach ($directories as $value)
		{
			echo '<option value='.$value['directory_id'].'>'.$value['directory'].'</option>';
		}
	}
	?>
	</select>
	<br /><br /><br /><br />
	<input type="submit" name="" value="Create File" onSubmit="" />
	</fieldset>
	</form>
<?php
}
?>

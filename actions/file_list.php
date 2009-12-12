<?php
/* file_list - JJTCode
 * 
 * Purpose:
 * To show all the files in a project as long as the user has sufficient permissions to do so
 * 
 * Recevies From:
 * 1. project_view = project_id via GET
 * 2. project_list = project_id via GET
 * 
 * Sends to:
 * 
 */
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate the project id, because it is used a lot for MySQL
$project_id = preg_replace("[^0-9]", "", $_GET['project_id']);
$project = new project($project_id);

// Get the level of the user
$level = $project->get_level($user->id);

if (is_bool($project))
{
	// If the project does not exist, tell them!
	?>
	<h2>Error</h2>
	<h3>The Requested project does not exist!</h3>
	<?php
}

elseif ($level<=0)
{
	// If they aren't allowed to view the project, tell them!
	?>
	<h2>Error</h2>
	<h3>Sorry you are not allowed to view this project.</h3>
	<?php
}

else
{	
	/* If they can view the project, use the $project object to fetch
	 * 
	 * */
	
	$files = $project->get_files();
	$directory = $project->get_directory();

	// If there are no files in the project, we will get an error with the foreach loop, so we'll stop that now!
	if (!is_bool($files))
	{
		// Show them the table
		echo '<table border="1">
		<tr>
			<th>Filename</th>
			<th>Last Modified</th>
			<th>Download</th>';
			echo ($level >= 3 ? "<th>Delete</th>" : ""); 
			
		echo '</tr>';
		
		foreach ($files as $value)
		{
			
			echo '<tr>';
			// If the file is a binary file, they cannot edit it with the stuff we have here, so we do not give them the option
			// to!
			if ($value['binary']==0)
			{
				echo '<td class="action"><a href="/?action='.($level >= 3 ? "file_edit" : "file_view").'&amp;file_id='.$value['id'].'" class="edit">'.$value['filename'].'.'.$value['extension'].'</a></td>';
			}
			else
			{
				echo '<td class="action"><a>'.$value['filename'].'.'.$value['extension'].'</a></td>';
			}
			
			echo '<td>'.date("r", $value['last_modified']).'</td>';
			echo '<td class="action"><a href="/download.php?file_id='.$value['id'].'" class="view">Download</a></td>';
		
			// If the level is enough to edit and delete files, give them the option!
			if ($level >= 3 )
			{
				echo '<td class="action"><a href="/?action=file_delete&amp;file_id='.$value['id'].'" class="delete">Delete</a></td>';
			}
			echo '</tr>';
		}
		
			if (!is_bool($directory))
			{
				
				foreach ($directory as $value)
				{
					echo '<tr>';
					echo '<td><a href="/?action=file_list&amp;project_id='.$project_id.'&amp;directory_id='.$value['directory_id'].'">'.$value['directory'].'</a></td>';
					echo '<td></td>';
					echo '<td></td>';
					if ($level>2)
					{
						
						echo '<td class="action"><a class="delete" href="/?action=directory_delete&amp;project_id='.$project_id.'&amp;directory_id='.$value['directory_id'].'">Delete</a></td>';
					}
					
					echo '</tr>';
				}
			}
			?>
		</table>
	
	
		<?php
	}
	elseif (!is_bool($directory))
			{
				echo '<table>';
						
				foreach ($directory as $value)
				{
					echo '<tr>';
					echo '<td class="action"><a href="/?action=file_list&amp;project_id='.$project_id.'&amp;directory_id='.$value['directory_id'].'">'.$value['directory'].'</a></td>';
					if ($level>2)
					{
						echo '<td></td>';
						echo '<td></td>';
						echo '<td class="action"><a class="delete" href="/?action=directory_delete&amp;project_id='.$project_id.'&amp;directory_id'.$value['directory_id'].'">'.$value['directory'].'</a></td>';
					}
				
					echo '</tr>';
				}
				echo '</table>';
			}
	// If there are no files, tell the user!
	else
	{
		echo '<h2>Error</h2>';
		echo '<h3>There are no files in this project/directory.</h3>';
	}
	
	echo '<br /><form name="jump1" class="jNice">
		  <select name="myjumpbox" OnChange="location.href=jump1.myjumpbox.options[selectedIndex].value">';
		  
	echo '<option selected>Please Select...</option>';
	echo '<option value="/?action=project_view&amp;project_id='.$project_id.'">View Profile</option>';

	// If they are allowed to edit files, allow them!
	if ($level>2)
	{
		echo '<option value="/?action=file_create&amp;project_id='.$_REQUEST['project_id'].'">Create File</option>';
		echo '<option value="/?action=file_upload&amp;project_id='.$_REQUEST['project_id'].'">Upload File</option>';
		echo '<option value="/?action=directory_create&amp;project_id='.$_REQUEST['project_id'].'">Create Directory</option>';
		
		// If they are allowed to delete the project, give them the option!
		if ($level>3)
		{
			echo '<option value="/?action=project_delete&amp;project_id='.$_REQUEST['project_id'].'">Delete Project. Deleting a project is permanent and your files will be unrecoverable!</option>';
		}
		
	}
	echo '</select>';
	
	
}
?>
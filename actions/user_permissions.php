<?php
/* user_permissions.php - JJTCode
 * 
 * Purpose:
 * To enable a user who is a project admin to be able to edit the permissions of other 
 * project users
 * 
 * Receives From:
 * 1. project_view - project_id when user clicks on 'edit_permissions'
 * 
 * Sends To:
 * 1. user_permissions_do - project_id, user_id, level via POST when user submits form
 *  
 * Requires Login: YES
 */
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// We have 2 user_id's, the one who is being edited and the one who is doing the editing
$user_id_edited = preg_replace("[^0-9]", "", $_REQUEST['user_id']);
$user_id_editing = $_SESSION['user_id'];

/* This SQL join is quite complicated. Basically we want the names and the id's of the projects that the user is an administrator of,
 * and therefore have the permissions to boost other's permissions. 
 * 
 * Therefore, we select them from the projects table, checking in the project_users table if the user has admin permissions
 * on that project */
$query="SELECT projects.project_name, projects.id, projects.default_level FROM project_users, projects WHERE project_users.user_id = '$user_id_editing' AND projects.id = project_users.project_id AND level = '4'";
$result=mysql_query($query);
$num = mysql_num_rows($result);

/* If 0 rows exist, the user has no projects where they are allowed to grant/remove other's privlegdes, and so we save 
 * confusion and server resources and stop here */
if ($num == 0)
{
	echo '<h2>Error</h2>';
	echo '<h3>There are no projects where you are allowed to grant others privledges!</h3>';
}

else
{
	$projects = array();
	
	// Then we load all their data into an array
	for ($i=0; $i < $num; $i++)
	{
		$projects[$i]=mysql_fetch_assoc($result);	
	}

	// Now we find out the levels of the user we are editing specially
	$query="SELECT * FROM project_users WHERE user_id = '$user_id_edited'";
	$result=mysql_query($query);
	$num=mysql_num_rows($result); // Get everything out of the MySQL database

	for ($i=0;$i<$num;$i++)
	{
		$name=mysql_result($result, $i, "project_id");
		$edit[$name]=mysql_result($result, $i, "level"); // Load up the column into the array using the name column
	}
	
	// Save some memory
	unset($name);
	unset($i);
	unset($num);
	
	
	
	// Now we show the user the permissions
	foreach ($projects as $value)
	{
		// Show the project name
		echo '<h2>'.$value['project_name'].'</h2>';
		// Setup the form
		echo '<form action="/" class="jNice">';
		echo '<p>Level:</p>';
		// Show the drop down menu
		echo '<select name="level">';
		echo '<option>';
		// If the project_id exists in the array, the user has a specific level, but if not we go to the default level
		if (isset($edit[$value['id']]))
		{
			echo $edit[$value['id']];
			$level=$edit[$value['id']];
		}
		else
		{
			echo $value['default_level'];
			$level=$value['default_level'];
		}
		echo '</option>';
		
		// Now we display all the other options
		for ($i=0; $i <= 4; $i++)
		{
			/* Firstly, we do not want to repeat the level, so if $i is equal to the level, start the loop again.
			 * Secondly, we also want to show some info about the various levels, so we have the switch to see what
			 * the level is, and then show the appropriate text */
			if ($i==$level)
			{
				continue;
			}
			
			echo '<option value='.$i.'>'.$i.'. ';
			
			switch ($i)
			{
				case 0:
				echo 'Banned User. User cannot access project, or see files.';
				break;
			
				case 1:
				echo 'Warned User. User has the same privledges as a standard user, but this indicates they have done
				something against the rules, and repeated offense may lead to them being banned.';
				break;
				
				case 2:
				echo 'Standard User. User can access project and view all files and directories.';
				break;
				
				case 3:
				echo 'Moderator. User can create, edit and delete project files and directories.';
				break;
				
				case 4:
				echo 'Administrator. As well as moderator privledges, the user can also change other user\'s privledges.';
				break;
			}
			echo '</option>';
		}
		echo '</select><br /><br /><br /><br />';
		echo '<input type="hidden" name="action" value="user_permissions_do" />';
		echo '<input type="hidden" name="project_id" value="'.$value['id'].'" />';
		echo '<input type="hidden" name="user_id" value="'.$user_id_edited.'" />';
		echo '<input type="submit" value="Change" />';
		echo '<br /><br />';
		echo '</form>';
	}
	
}




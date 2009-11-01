<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Lets see what the user wants to do
if ($_REQUEST['do']=="create")
{
	// Check to see whether the user is allowed to create projects
	if ($_SESSION['level']<=2)
	{
		echo '<h3>You are not allowed to create projects!</h3>';
	}
	
	else
	{
		// Validate the data and generate the variables
		$error="";
		$time=time();
		
		if (!empty($_POST['project_name']))
		{
			$project=preg_replace("[^0-9a-zA-Z]", "", addslashes($_POST['project_name']));
			$link=preg_replace("[^0-9a-zA-Z]", "", trim(addslashes($_POST['project_name'])));
		}
		else
		{
			$error.="The Project Name is Empty! ";
		}
	
		$description=addslashes($_POST['description']);
		$level=(int)$_POST['level'];
	
		// Check to see whether level or visible are empty, if they are, set a default
		if (empty($level) || $level<0 || $level>5)
		{
			$level = 2;
		}
	
		if (empty($visible))
		{
			$visible = 1;
		}
		else
		{
			$visible = 2;
		}
	
		// End variable validation
	
		// Now we check to see whether the project name already exists
		$query="SELECT * FROM projects WHERE project_name = '$project'";
		$result=mysql_query($query);
		
		if (mysql_num_rows($result)>0)
		{
			$error.="The project name already exists! ";
		}
	
		// Finally, we check to see how many projects the user has created, and if its over 3, we will not allow them to create
		// anymore
		
		$query="SELECT * FROM projects WHERE creator = '$_SESSION[user_id]'";
		$result=mysql_query($query);
		
		if (mysql_num_rows($result)>=3)
		{
			$error.="You have created at least 3 projects, which is the limit for an account";
		}
		
		// Check the error variable
		if (!empty($error))
		{
			echo "<h2>Error!</h2>";
			echo "<p class='warning'>".$error."</p>";
		}
		// End validation, if everything is fine, go ahead an insert!
		else
		{
			// Create the project in the database
			$query="INSERT INTO projects VALUES ('$project', '$link', '$description', '$time', '$time', '$level', '$visible', '$_SESSION[user_id]', NULL)";
			mysql_query($query);
			echo mysql_error();
	
			// Give the user administrative rights to the project
			// Firstly, we need to get the project id
			$query="SELECT * FROM projects WHERE project_name = '$project'";
			$result=mysql_query($query);
			$project_id = mysql_result($result, 0, "id");
	
			// Now we insert the data into the project_users table to give the permissions
			$query="INSERT INTO project_users VALUES ('$_SESSION[user_id]', '$project_id', '5')";
			mysql_query($query);
	
			// Redirect the user to the project page
			?>
			<script language="javascript" type="text/javascript">
			window.location = "/?action=project_view&project_id=<?php echo $project_id; ?>";
			</script>
			<noscript>Please click <a href="/?action=project_view&amp;project_id=<?php echo $project_id; ?>">here</a> to continue</noscript>
			<?php
		
		}
	}
}

elseif ($_REQUEST['do']=="edit")
{
	// Validate the ID
	$project_id=preg_replace("[^0-9]", "", $_POST['project_id']);
	
	// Check to see whether they are allowed to edit the project
	// They need to be an admin to do it, so make sure they are!
	$level=get_project_level($_SESSION['user_id'], $project_id, false);
	
	if ($level==4)
	{
		// Validate the project name
		$project=preg_replace("[^0-9a-zA-Z]", "", $_POST['project_name']);
		$description=addslashes($_POST['description']);
		$time=time();
		
		// Update the database
		$query="UPDATE projects SET project_name = '$project', description = '$description', last_modified = '$time' WHERE id = '$project_id'";
		mysql_query($query);
		
		// Redirect the user
		?>
		<script language="javascript" type="text/javascript">
		window.location = "/?action=project_view&project_id=<?php echo $project_id; ?>";
		</script>
		<noscript>Please click <a href="/?action=project_view&amp;project_id=<?php echo $project_id; ?>">here</a> to continue</noscript>
		<?php
	}
	else
	{
		echo '<h2>Error!</h2><h3>Sorry you are not allowed to edit this project!</h3>';
	}
}
?>
	
	
	
	

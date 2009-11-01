<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Validate the user id!
$user_id = preg_replace("[^0-9]", "", $_REQUEST['user_id']);

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
	// Load the user's info!
	$query="SELECT * FROM users WHERE id = '$user_id'";
	$result=mysql_query($query);
	$user=mysql_fetch_assoc($result);

	// See how many projects the user is involved in
	$query="SELECT projects.project_name, projects.id FROM project_users, projects WHERE project_users.user_id = '$user_id' AND projects.id = project_users.project_id AND level > 3 AND projects.visible = '1'";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);

	echo '<h2>'.$user['username'].'</h2>';

	echo '<h3>Statistics</h3>';
	echo '<p>Involved in '.$num.' projects</p><br />';
	
	// Show the user the projects that this user is involved in
	for ($i=0;$i<$num;$i++)
	{
		$projects[$i]=mysql_fetch_assoc($result);
		echo '<a href="/?action=file_list&amp;project_id='.$projects[$i]['id'].'">'.$projects[$i]['project_name'].'</a><br /><br />';
	}
}
	?>


<?php
error_reporting(-1);
// Start the session!
session_start();


define("jjtcode", 0.1);
// Load up all the function requires
require("sources/jjtsql.php");
require("sources/sql.php");
require("sources/functions.php");

// If the person is not logged in, they are a guest and so we give them a user id of 0
if (!login_check())
{
	$_SESSION['user_id']=0;
	$_SESSION['level']=1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JJTCode</title>

<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->

<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>

<body>
	<div id="wrapper">
        <ul id="mainNav">
        	<li id="home"><a href="/"><img src="/icon/go-home.png" />HOME</a></li> <!-- Use the "active" class for the active menu item  -->
        	<li id="projects"><a href="/?action=project_list"><img src="/icon/projects.png" />PROJECTS</a></li> 
			<?php
			// The icons that require the user to be logged in
			if (login_check())
			{
				echo '<li id="users"><a href="/?action=user_view"><img src="/icon/users.png" />USERS</a></li>';
				echo'<li id="message"><a href="/?action=message_inbox"><img src="/icon/message.png" />MESSAGING</a></li>';
				echo '<li class="logout"><a href="/logout.php">LOGOUT<img src="/icon/logout.png" /></a></li>';
			}
			// If the user is not logged in, just show them the login button
			else
			{
				echo '<li class="logout"><a href="/?action=login">LOGIN<img src="/icon/login.png" /></a></li>';
			}
			?>
			<li id="faq"><a href="/?action=faq"><img src="/icon/faq.png" />FAQ</a></li>
			<li id="about"><a href="/?action=about"><img src="/icon/about.png" />ABOUT</a></li>
        	</li>
        </ul>
        <!-- // #end mainNav -->
        
        <div id="containerHolder">
			<div id="container">
        		  
                <!-- // #sidebar -->
                
                <div id="main">
				<br />
                	<?php
					// Lets see what they want to do
					if (isset($_REQUEST['action']))
					{
						////////////////////////////////////////////////////
						// Validation
						$action=trim($_REQUEST['action']);
						$action=preg_replace("/[^a-z_]/", "", $action);
			
						// Check to see whether the requested file exists!
						if (file_exists("actions/".$action.".php"))
						{
							// If they want to do something, lets do it!
							require('actions/'.$action.'.php');
						}
						// But if the file doesn't exist, tell the user!
						else
						{
							echo '<h2>Error</h2>';
							echo '<h3>The Requested Action Does not Exist!</h3>';
						}
						
						// End Validation
						////////////////////////////////////////////////////
					}
					
					// But there is nothing to do, and they are logged in, show the dashboard!
					elseif (login_check())
					{
						require("actions/dashboard.php");
					}
					
					// And finally, if there is nothing to do, and they aren't logged in, show the home page!
					else
					{
						require("actions/index.php");
					}
					?>
					<br />
                </div>
                <!-- // #main -->
                
                <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
        
        <p id="footer">JJTComputing, unless mentioned, does not create, endorse, or control the code that is on the website. Use at your own risk! <br />&copy; JJTComputing 2009</p>
    </div>
    <!-- // #wrapper -->
</body>
</html>

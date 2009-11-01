<?php
/*
 * All the non JJTSQL functions are contained in this script
 * 
 * bool login_check()
 * Checks to see whether the user is logged in. It does not check whether they are banned, or what their
 * level is.
 * 
 * array get_users()
 * Returns a list of all the users in the database
 * 
 * array get_user_info($user_id)
 * Returns a particular user's info
 * 
 * int get_project_level($user_id, $project_id, $validate=true)
 * Gets the users level for a project, whether they have a special level or just the standard one. It will also
 * validate the input unless validate is specified to be false
 * 
 * string check_email_address($email)
 * Makes sure an email address is valid and returns the validated string.
 * 
 * bool mail_send($user_id, $sending_user, $title, $message)
 * Sends a private message to the specified user
 *
 */
function login_check()
{

   if (isset($_COOKIE['user']) && isset($_SESSION['user']) && $_SESSION['user']==$_COOKIE['user'])
   { //Make sure all the cookies and session variables have been set (first test)
       return true;
   }
   
   else
   {
       return false;
   }
   
}

function get_users()
{
    $array=jjtsql_num_array_load("users", "login", "id", "DESC"); // Get all the username's from the database
    return $array;
}

function get_user_info($user)
{
    $query="SELECT * FROM users WHERE login = '$user'"; // Get all the user's info
    $result=mysql_query($query);

    if (@mysql_num_rows($result)==0)
    { // If the user does not exist, return false!
	    return false;
    }
	
    $array=mysql_fetch_assoc($result); // But if they do exist get their details!
    return $array;
}

function get_project_level($user_id, $project_id, $validate = true)
{
	// Most of the data comes from outside sources, so it is easier to validate it now, but if it doesn't or
	// we already have validated it, its a waste of server resources to do it again
	if ($validate)
	{
		$project_id = preg_replace("[^0-9]", "", $project_id);
	}
	
	// Check whether this user has any special permissions for this project
	$query="SELECT * FROM project_users WHERE user_id = '$user_id' AND project_id = '$project_id'";
	$result=mysql_query($query);

	// If there are no special permissions, lets see what the standard ones are
	if (mysql_num_rows($result)==0)
	{
		$query="SELECT * FROM projects WHERE id = '$project_id'";
		$result=mysql_query($query);
		
		// Check whether the project exists
		if (mysql_num_rows($result)==0)
		{ // And if it doesn't, return false
			return false;
		}
		
		else
		{ // But if it does, return the level!
			$level=mysql_result($result, 0 ,"default_level");
		}
	}

	// But if there are special permissions, load them!
	else
	{
		$level=mysql_result($result, 0, "level");
	}
	
	return $level;
}

function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

function mail_send($user_id, $sending_user, $title, $message)
{
	
}


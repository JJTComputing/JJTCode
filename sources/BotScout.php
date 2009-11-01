<?php 
/////////////////////////////////////////////////////
// "Universal" API code for use with the BotScout.com API
// version 1.40 Code by MrMike / LDM 2-2009 

/* 
/////////////////
2-5-2008: added conditional test to force the use 
of the file_get_contents() function unless the version of 
PHP used doesn't have it. 
/////////////////
2-15-2009: renamed '$data' var to '$returned_data' to avoid 
a conflict with phpBB code. 
2-15-2009: Sanity check now only prints if diagnostic 
output is enabled.
Thanks to "Boris" for the changes above.
/////////////////
2-19-2009: Changed default test type to 'MULTI' for speed 
and efficiency. 
/////////////////
*/


/////////////////////////////////////////////////////

////////////////////////
// init vars
$diag='';
$bs_data='';
$botdata='';
$apptype='';
$send_alerts='';
$toText='';
$fromText='';
$subjectText='';
$msgText='';
////////////////////////

/////////////////////////////////////////////////////
// CONFIGURATION START

// Removed most of this section: we are only using it for PHPBB! So we can remove the excess overhead

// your optional API key (if you don't have one 
// you can get one here: http://botscout.com/)
$APIKEY = 'm7XobnTLhfGAPvC';


// CONFIGURATION END
/////////////////////////////////////////////////////
// get the IP address
$XIP = $_SERVER['REMOTE_ADDR']; 
$XUSER = $data['username'];
$XMAIL = $data['email'];


////////////////////////

// make the url compliant with urlencode()
$XMAIL =urlencode($XMAIL);


// run the API query...the default is to check the email address. It's usually the most 
// reliable indicator or bot 'signature' field, but you can change this to use the Ip or 
// the username if you like. You could check all three if you wanted, but usually the 
// email address alone is sufficient. 


// testing for an email address and IP
$apiquery = "http://botscout.com/test/?multi&mail=$XMAIL&ip=$XIP&key=$APIKEY";

////////////////////////
// Use cURL or file_get_contents()?
// Use file_get_contents() unless not available

if(function_exists('file_get_contents'))
{
	// Use file_get_contents
	$returned_data = file_get_contents($apiquery);
}
// take the returned value and parse it (standard API, not XML)
$botdata = explode('|', $returned_data); 

// sample 'MULTI' return string (standard API, not XML)
// Y|MULTI|IP|4|MAIL|26|NAME|30

// $botdata[0] - 'Y' if found in database, 'N' if not found, '!' if an error occurred 
// $botdata[1] - type of test (will be 'MAIL', 'IP', 'NAME', or 'MULTI') 
// $botdata[2] - descriptor field for item (IP)
// $botdata[3] - how many times the IP was found in the database 
// $botdata[4] - descriptor field for item (MAIL)
// $botdata[5] - how many times the EMAIL was found in the database 
// $botdata[6] - descriptor field for item (NAME)
// $botdata[7] - how many times the NAME was found in the database 


if(substr($returned_data, 0,1) == '!'){
	// if the first character is an exclamation mark, an error has occurred  
	print "An Error has occured. Please try again.";
	exit;
}


// this example tests the email address and IP to see if either of them appear 
// in the database at all. Either one is a fairly good indicator of bot identity. 
if($botdata[3] > 0 || $botdata[5] > 0){ 
	print $data; 

	// your 'rejection' code would go here.... 
	// for example, print a fake error message and exit the process. 
	print "Our system has identified you as a SpamBot. If you are not a bot please contact the administrator.";
	exit;

}
////////////////////////


?>

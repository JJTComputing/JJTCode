// Script 13.3 - checkusername.js

/*	This page does all the magic for applying
 *	Ajax principles to a registration form.
 *	The users's chosen username is sent to a PHP 
 *	script which will confirm its availability.
 */

// Function that starts the Ajax process:
function check_username(username) {

	// Confirm that the object is usable:
	if (ajax) { 
		
		// Call the PHP script.
		// Use the GET method.
		// Pass the username in the URL.
		ajax.open('get', '/ajax/user_search.php?username=' + encodeURIComponent(username));
		
		// Function that handles the response:
		ajax.onreadystatechange = handle_check;
		
		// Send the request:
		ajax.send(null);

	} else { // Can't use Ajax!
		document.getElementById('messages').innerHTML = 'The server cannot be contacted. Please make sure that your browser can support AJAX.';
	}
	
} // End of check_username() function.

// Function that handles the response from the PHP script:
function handle_check() {

	// If everything's OK:
	if ( (ajax.readyState == 4) && (ajax.status == 200) ) {

		// Assign the returned value to the hidden input:
		document.getElementById('messages').innerHTML = ajax.responseText;
		
	}
	
} // End of handle_check() function.

// This sets up the form to send the PM to the right person!
function user_set(username, user_id)
{
	// Right then we set up the forms
	document.message.user_to.value = username;

	document.message.user_id_to.value = user_id;
}
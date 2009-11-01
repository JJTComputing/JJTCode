<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// If they are already logged in, they do not need to register!
if (!login_check())
{
	?>
	<form action="/" method="POST" id="jNice">
	<fieldset>
	<label>Login Name:</label><input type="text" name="login" class="text-long" /><br /><br /><br />
	<label>Username:</label><input type="text" name="username" class="text-long" /><br /><br /><br />
	<label>Password:</label><input type="password" name="pass1" class="text-long" /><br /><br /><br />
	<label>Password Again:</label> <input type="password" name="pass2" class="text-long" /><br /><br /><br />
	<label>Email:</label> <input type="text" name="email" class="text-long" /><br /><br /><br />
	<div id="license">
	<?php
	require("actions/license.php");
	?>
	</div>
	<input type="hidden" name="action" value="register_do" />
	<input type="submit" value="Register" />
	</fieldset>
	</form>
	<?php
}
else
{
	// If they are logged in, show an error!
	echo '<h2>Error</h2><p>You are already logged in, you do not need to register!</p>';
}

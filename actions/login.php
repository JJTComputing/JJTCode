<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}
?>
<form action="/login.php" method="POST" class="jNice">
<fieldset>
<p><label>Username:</label><input type="text" name="user" class="text-long" /></p>
<p><label>Password:</label><input type="password" name="pass" class="text-long" /></p>
<p><input type="submit" value="Login" /></p>

<p>In order to login you must be registered. If you register, you can create projects, and view more projects.</p>
<p><a href="/?action=register"><button class="" type="submit" name="" id=""><span><span>Register</span></span></button></a></p>
</fieldset>

</form>

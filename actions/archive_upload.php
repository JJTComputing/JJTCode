<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}
?>
<h2>Upload File</h2>
<form enctype="multipart/form-data" action="/" method="POST" id="form" name="post">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
<label>File: </label> <input name="zip" type="file"> <br />
<input type="hidden" name="action" value="archive_upload_do">
<input type="hidden" name="project_id" value="" />
<input type="hidden" name="do" value="create" />
<br /><br />
<input type="submit" value="Send File">
</form>

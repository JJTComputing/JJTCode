<?php
if (!defined("jjtcode"))
{
  die("Hacking Attempt!");
}

// Firstly we open the zip file
$zip = zip_open($_FILES['zip']['tmp_name']);

if ($zip)
{
	$i = 0;
	// Loop around all the files in the directory
	while ($zip_entry = zip_read($zip))
    {
		echo "<p>";
		echo "Name: " . zip_entry_name($zip_entry) . "<br />";
		
		if (zip_entry_open($zip, $zip_entry))
		{
			echo $i;
			$i++;
			$content=zip_entry_read($zip_entry);
		}
		
    echo "</p>";
  }
zip_close($zip);
}
?>

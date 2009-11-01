<?php 
$mysql_username="a9281061_tristan";
$mysql_password="7PoplarLane";
$mysql_database="a9281061_tristan";
$mysql_server="mysql3.000webhost.com";
mysql_connect($mysql_server, $mysql_username, $mysql_password); 
@mysql_select_db($mysql_database) or die("Selected database does not exist");
define("db_prefix", "");
?>

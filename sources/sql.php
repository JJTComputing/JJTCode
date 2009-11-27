<?php 
$mysql_username="root";
$mysql_password="7PoplarLane";
$mysql_database="jjtcode";
$mysql_server="localhost";
mysql_connect($mysql_server, $mysql_username, $mysql_password); 
@mysql_select_db($mysql_database) or die("Selected database does not exist");
define("db_prefix", "");
?>

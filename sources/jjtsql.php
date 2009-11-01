<?php
/*
Copyright JJTComputing and Tristan Millington 2009
http://jjtcomputing.co.uk
MySQL Functions
 
sources/jjtsql.php

JJTSQL Functions
* 
* Last Modified: 10/10/09
* By: Tristan Millington

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. The name of the author may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

function jjtsql_num_array_load($table, $column, $orderby, $asc)
{
	$query="SELECT * FROM $table ORDER BY $orderby $asc";
	$result=mysql_query($query); // Find the table they want to load up
	$num=mysql_num_rows($result);
	$i=0;

	while ($i<$num)
	{
		$array[$i]=mysql_result($result, $i, $column);
		$i++; // Load up the table into the array
	}
	
	return $array; // Give them the result
}

function jjtsql_name_array_load($table, $namecolumn, $valuecolumn)
{
	$query="SELECT * FROM $table";
	$result=mysql_query($query);
	$num=mysql_num_rows($result); // Get everything out of the MySQL database
	$i=0;

	while ($i<$num)
	{
		$name=mysql_result($result, $i, $namecolumn);
		$array[$name]=mysql_result($result, $i, $valuecolumn); // Load up the column into the array using the name column
		$i++;
	}
	
	return $array;
}

function jjtsql_field_array_load($table, $column, $value)
{
	$query = "SELECT * FROM $table WHERE $column = '$value'";
	$result = mysql_query($query);
	$num = mysql_num_rows($result); // Find out how many rows there are

	for($i = 0; $i < $num; $i++) 
	{
		$array[$i] = mysql_fetch_assoc($result); // Load up the result into an array
	}

	return $array; // And give it back to them!!
}

function jjtsql_table_field_array_load($table)
{
	$query="SELECT * FROM $table";
	$result=mysql_query($query);

	if (!$result) // If the table does not exist, tell the user!
	{
		return false;
	}

	else
	{
		$num = mysql_num_rows($result); // Find out how many rows there are

		for($i = 0; $i < $num; $i++) 
		{
			$array[$i] = mysql_fetch_assoc($result); // Load up the result into an array
		}

	return $array;
	}
}

function jjtsql_table_null_load($table, $field1, $value1, $field2)
{
	$query="SELECT * FROM $table WHERE $field1 = '$value1' AND $field2 IS NULL";
	$result=mysql_query($query);
	$num = mysql_num_rows($result);
	
	
	
	if (!$result || $num==0) // If the table does not exist, tell the user!
	{
		return false;
	}
	
	for($i = 0; $i < $num; $i++) 
	{
		$array[$i] = mysql_fetch_assoc($result); // Load up the result into an array
	}
	
	return $array;
}
	
function jjtsql_table_double_load($table, $field1, $value1, $field2, $value2)
{
	$query="SELECT * FROM $table WHERE $field1 = '$value1' AND $field2 = '$value2'";
	$result=mysql_query($query);
	$num = mysql_num_rows($result);
	
	if (!$result || $num==0) // If the table does not exist, tell the user!
	{
		return false;
	}
	
	for($i = 0; $i < $num; $i++) 
	{
		$array[$i] = mysql_fetch_assoc($result); // Load up the result into an array
	}
	
	return $array;
}
?>

<?php

function Getbasesite($file)
{
	$find = '/';
	$after_find = substr(strrchr($file, $find), 1); 
	$strlen_str = strlen($after_find); 
	$result = substr($file, 0, -$strlen_str); 
	
	return $result; 
}

	$sitename = $_SERVER["SERVER_NAME"];
	$sitenamepath = $_SERVER["SCRIPT_NAME"];
	$pathsite = $sitename.$sitenamepath;

	include ("./etc/config.$sitename.php");
	
	$file = $pathsite;
	
	$site = Getbasesite($file);

	$db_conn = mysql_connect($mysql_hostname, $mysql_username, $mysql_password); 
	mysql_select_db($mysql_dbname);

	$tbl_List = mysql_query("SHOW TABLES");
	$table_Count = mysql_num_rows($tbl_List);
	
	// NO schema found!
	if ($table_Count == 0) {
		$sql = explode(';', file_get_contents ('./doc/sql/mysql-tables.sql'));
		$n = count ($sql) - 1;
		for ($i = 0; $i < $n; $i++) {
			$query = $sql[$i];
			$result = mysql_query ($query)
			or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
		}
	}
	die("<CENTER>Mining Buddy Tables Created <a href=\"./index.php\">Start</a> Mining Buddy.</CENTER>");	

?>
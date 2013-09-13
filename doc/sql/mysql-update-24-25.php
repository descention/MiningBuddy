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
	
	$upgrade=$_REQUEST['upgrade'];

	include ("./etc/config.$sitename.php");
	
	$file = $pathsite;
	
	$site = Getbasesite($file);

	$db_conn = mysql_connect($mysql_hostname, $mysql_username, $mysql_password);
	mysql_select_db($mysql_dbname);



if ($upgrade=='1' ){
	
	echo "Add Tables for Dynamicness";
	echo "<br>";
	$configupgrade0 = mysql_query("CREATE TABLE IF NOT EXISTS `opTypes` ( `id` int(12) NOT NULL AUTO_INCREMENT, `opName` varchar(256) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `opName` (`opName`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	$configupgrade1 = mysql_query("ALTER TABLE `runs` ADD `optype` varchar( 256 ) NOT NULL DEFAULT '' AFTER `tmec` ;");
	$configupgrade2 = mysql_query("ALTER TABLE `orevalues` ADD `item` varchar( 256 ) NOT NULL DEFAULT '' AFTER `time` ;");
	$configupgrade3 = mysql_query("ALTER TABLE `orevalues` ADD `worth` decimal( 16,2 ) NOT NULL DEFAULT '0.00' AFTER `item` ;");
	$configupgrade4 = mysql_query("ALTER TABLE `hauled` ADD `item` varchar( 256 ) NOT NULL DEFAULT '' AFTER `time` ;");
	$configupgrade5 = mysql_query("ALTER TABLE `hauled` ADD `quantity` decimal( 16,2 ) NOT NULL DEFAULT '0.00' AFTER `item` ;");
	$configupgrade6 = mysql_query("UPDATE `config` SET `value` = '0' WHERE `config`.`name` = 'Lotto' ;"); 

	echo "Updating the config version number!";
	echo "<br>";
	$configupgrade7 = mysql_query("UPDATE config SET value='25' WHERE name='version'");
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=http://".$site.">Click here to Login</a>";
	
}else{

?>
<br>
<center>
<body bgcolor="#2E2E2E">
<font color= white>
<body link="#00FF00" vlink="##00FF00" alink="#FF0000">
<br>
<br>
<br>
<br>
<br>
<br>
<br><br><br><br><br><br>
<?php
echo "This is the $VERSION SQL upgrade page.<br>";
?>
When you are ready to upgrade your Mining Buddy Plus Database to the newest version click the link below<br>
<a href="mysql-update-24-25.php?upgrade=1"><h1>Upgrade Now to Database Version 
<?php
echo " " . $SQLVER;
?>
</h1> (from version 
<?php
echo " " . $SQLVER - 1;
?>
)</a>
<br>
<?
}
?>
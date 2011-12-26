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
	
	echo "Add Tables for Lottory";
	echo "<br>";
	$configupgrade0 = mysql_query("CREATE TABLE IF NOT EXISTS `lotteryTickets` (`id` int(5) NOT NULL AUTO_INCREMENT, `ticket` int(5) NOT NULL, `drawing` int(4) NOT NULL, `owner` int(5) NOT  NULL DEFAULT '-1', `isWinner` tinyint(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
	$configupgrade1 = mysql_query("CREATE TABLE IF NOT EXISTS `lotto` (`drawing` int(5) NOT NULL AUTO_INCREMENT, `opened` int(12) NOT NULL, `closed` int(12) NOT NULL, `isOpen` tinyint(1) NOT NULL DEFAULT '0', `winningTicket` int(5) DEFAULT NULL, `winner` int(5) DEFAULT NULL, `potSize` int(8) DEFAULT NULL, PRIMARY KEY (`drawing`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;");
	$configupgrade2 = mysql_query("ALTER TABLE `users` ADD `isLottoOfficial` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `isOfficial` ");
	$configupgrade3 = mysql_query("ALTER TABLE `users` ADD `canPlayLotto` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `isLottoOfficial` ;");
	$configupgrade4 = mysql_query("ALTER TABLE `users` ADD `lottoCredit` INT( 5 ) NOT NULL DEFAULT '0' AFTER `isAccountant` ;");
	$configupgrade5 = mysql_query("ALTER TABLE `users` ADD `lottoCreditsSpent` INT( 5 ) NOT NULL DEFAULT '0' AFTER `lottoCredit` ;");
	$configupgrade6 = mysql_query("UPDATE `config` SET `value` = '0' WHERE `config`.`name` = 'Lotto' ;"); 
	
echo "Updating the config version number!";
	echo "<br>";
	$configupgrade7 = mysql_query("UPDATE config SET value='24' WHERE name='version'");
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=http://".$site.">Click here to Login</a>";
	
}else{

?>
<center>
This is the Mining Buddy SQL upgrade page.<br>
When you are ready to upgrade you Mining Buddy Install to the newest version click the link below<br>
<a href="mysql-update-23-24.php?upgrade=1">Upgrade Now</a>
<?
}
?>
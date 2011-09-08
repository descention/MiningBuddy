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
	
	echo "Add Configuration for Using Eve-Central Market Values";
	echo "<br>";
	$configupgrade0 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('useMarket','1');");
	$configupgrade1 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('useRegion','10000002');");
	$configupgrade2 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('orderType','0');");
	$configupgrade3 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('priceCriteria','2');");
	
	echo "Create Market Cache Table";
	echo "<br>";
	$configupgrade4 = mysql_query("CREATE TABLE IF NOT EXISTS `itemList` (`updateTime` varchar(10) DEFAULT NULL, `itemName` varchar(31) DEFAULT NULL, `itemID` varchar(10) DEFAULT NULL, `value` int(1) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	
	echo "Add Items into Market Cache Table";
	echo "<br>";
	$configupgrade5 = mysql_query("INSERT INTO `itemList` (`updateTime`, `itemName`, `itemID`, `value`) VALUES ('1315077942', 'Arkonor', '22', 1),('1315077942', 'Crimson Arkonor', '17425', 1),('1315077942', 'Prime Arkonor', '17426', 1),('1315077942', 'Bistot', '1223', 1),('1315077942', 'Triclinic Bistot', '17428', 1),('1315077942', 'Monoclinic Bistot', '17429', 1),('1315077942', 'Crokite', '1225', 1),('1315077942', 'Sharp Crokite', '17432', 1),('1315077942', 'Crystalline Crokite', '17433', 1),('1315077942', 'Dark Ochre', '1232', 1),('1315077942', 'Onyx Ochre', '17436', 1),('1315077942', 'Obsidian Ochre', '17437', 1),('1315077942', 'Gneiss', '1229', 1),('1315077942', 'Iridescent Gneiss', '17865', 1),('1315077942', 'Prismatic Gneiss', '17866', 1),('1315077942', 'Hedbergite', '21', 1),('1315077942', 'Glazed Hedbergite', '17441', 1),('1315077942', 'Vitric Hedbergite', '17440', 1),('1315077942', 'Hemorphite', '1231', 1),('1315077942', 'Vivid Hemorphite', '17444', 1),('1315077942', 'Radiant Hemorphite', '17445', 1),('1315077942', 'Jaspet', '1226', 1),('1315077942', 'Pure Jaspet', '17448', 1),('1315077942', 'Pristine Jaspet', '17449', 1),('1315077942', 'Kernite', '20', 1),('1315077942', 'Luminous Kernite', '17452', 1),('1315077942', 'Fiery Kernite', '17453', 1),('1315077942', 'Mercoxit', '11396', 1),('1315077942', 'Magma Mercoxit', '17869', 1),('1315077942', 'Vitreous Mercoxit', '17870', 1),('1315077942', 'Omber', '1227', 1),('1315077942', 'Silvery Omber', '17867', 1),('1315077942', 'Golden Omber', '17868', 1),('1315077942', 'Spodumain', '19', 1),('1315077942', 'Bright Spodumain', '17466', 1),('1315077942', 'Gleaming Spodumain', '17467', 1),('1315077942', 'Plagioclase', '18', 1),('1315077942', 'Azure Plagioclase', '17455', 1),('1315077942', 'Rich Plagioclase', '17456', 1),('1315077942', 'Pyroxeres', '1224', 1),('1315077942', 'Solid Pyroxeres', '17459', 1),('1315077942', 'Viscous Pyroxeres', '17460', 1),('1315077942', 'Scordite', '1228', 1),('1315077942', 'Condensed Scordite', '17463', 1),('1315077942', 'Massive Scordite', '17464', 1),('1315077942', 'Veldspar', '1230', 1),('1315077942', 'Concentrated Veldspar', '17470', 1),('1315077942', 'Dense Veldspar', '17471', 1),('1315077942', 'Blue Ice', '16264', 1),('1315077942', 'Clear Icicle', '16262', 1),('1315077942', 'Dark Glitter', '16267', 1),('1315077942', 'Enriched Clear Icicle', '17978', 1),('1315077942', 'Gelidus', '16268', 1),('1315077942', 'Glacial Mass', '16263', 1),('1315077942', 'Glare Crust', '16266', 1),('1315077942', 'Krystallos', '16269', 1),('1315077942', 'Pristine White Glaze', '17976', 1),('1315077942', 'Smooth Glacial Mass', '17977', 1),('1315077942', 'Thick Blue Ice', '17975', 1),('1315077942', 'White Glaze', '16265', 1),('1315077942', 'Condensed Alloy', '11739', 1),('1315077942', 'Crystal Compound', '11741', 1),('1315077942', 'Precious Alloy', '11737', 1),('1315077942', 'Sheen Compound', '11732', 1),('1315077942', 'Gleaming Alloy', '11740', 1),('1315077942', 'Lucent Compound', '11738', 1),('1315077942', 'Dark Compound', '11735', 1),('1315077942', 'Motley Compound', '11733', 1),('1315077942', 'Lustering Alloy', '11736', 1),('1315077942', 'Glossy Compound', '11724', 1),('1315077942', 'Plush Compound', '11725', 1),('1315077942', 'Opulent Compound', '11734', 1),('1315077942', 'Cartesian Temporal Coordinator', '30024', 1),('1315077942', 'Central System Controller', '30270', 1),('1315077942', 'Defensive Control Node', '30269', 1),('1315077942', 'Electromechanical Hull Sheeting', '30254', 1),('1315077942', 'Emergent Combat Analyzer', '30248', 1),('1315077942', 'Emergent Combat Intelligence', '30271', 1),('1315077942', 'Fused Nanomechanical Engines', '30018', 1),('1315077942', 'Heuristic Selfassemblers', '30022', 1),('1315077942', 'Jump Drive Control Nexus', '30268', 1),('1315077942', 'Melted Nanoribbons', '30259', 1),('1315077942', 'Modified Fluid Router', '30021', 1),('1315077942', 'Neurovisual Input Matrix', '30251', 1),('1315077942', 'Powdered C-540 Graphite', '30019', 1),('1315077942', 'Resonance Calibration Matrix', '30258', 1),('1315077942', 'Thermoelectric Catalysts', '30252', 1),('1315077942', 'Fullerite-C28', '30375', 1),('1315077942', 'Fullerite-C32', '30376', 1),('1315077942', 'Fullerite-C50', '30370', 1),('1315077942', 'Fullerite-C60', '30371', 1),('1315077942', 'Fullerite-C70', '30372', 1),('1315077942', 'Fullerite-C72', '30373', 1),('1315077942', 'Fullerite-C84', '30374', 1),('1315077942', 'Fullerite-C320', '30377', 1),('1315077942', 'Fullerite-C540', '30378', 1),('1315077942', 'Neural Network Analyzer', '30744', 1),('1315077942', 'Sleeper Data Library', '30745', 1),('1315077942', 'Ancient Coordinates Database', '30746', 1),('1315077942', 'Sleeper Drone AI Nexus', '30747', 1);");

echo "Updating the config version number!";
	echo "<br>";
	$configupgrade6 = mysql_query("UPDATE config SET value='23' WHERE name='version'");
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=http://".$site.">Click here to Login</a>";
	
}else{

?>
<center>
This is the Mining Buddy SQL upgrade page.<br>
When you are ready to upgrade you Mining Buddy Install to the newest version click the link below<br>
<a href="mysql-update-22-23.php?upgrade=1">Upgrade Now</a>
<?
}
?>
<?php

function Getbasesite($file)
{
	$find = '/';
	$after_find = substr(strrchr($file, $find), 1); 
	$strlen_str = strlen($after_find); 
	$result = substr($file, 0, -$strlen_str); 
	
	return $result; 
}

$sitename = $_SERVER["HTTP_HOST"];
$sitenamepath = $_SERVER["SCRIPT_NAME"];
$pathsite = $sitename.$sitenamepath;

$upgrade=$_REQUEST['upgrade'];
include ("./etc/config.$sitename.php");

global $SQLVER;

if ($upgrade=='1' ){
	$file = $pathsite;

	//$site = Getbasesite($file);

	$db_conn = mysql_connect($mysql_hostname, $mysql_username, $mysql_password);
	mysql_select_db($mysql_dbname);

	echo "Add Items";
	echo "<br>";
	mysql_query("INSERT INTO `itemList` (`updateTime`, `itemName`, `itemID`, `value`) VALUES
('1315077942', 'Arkonor', '22', 0.00),
('1315077942', 'Crimson Arkonor', '17425', 0.00),
('1315077942', 'Prime Arkonor', '17426', 0.00),
('1315077942', 'Bistot', '1223', 0.00),
('1315077942', 'Triclinic Bistot', '17428', 0.00),
('1315077942', 'Monoclinic Bistot', '17429', 0.00),
('1315077942', 'Crokite', '1225', 0.00),
('1315077942', 'Sharp Crokite', '17432', 0.00),
('1315077942', 'Crystalline Crokite', '17433', 0.00),
('1315077942', 'Dark Ochre', '1232', 0.00),
('1315077942', 'Onyx Ochre', '17436', 0.00),
('1315077942', 'Obsidian Ochre', '17437', 0.00),
('1315077942', 'Gneiss', '1229', 0.00),
('1315077942', 'Iridescent Gneiss', '17865', 0.00),
('1315077942', 'Prismatic Gneiss', '17866', 0.00),
('1315077942', 'Hedbergite', '21', 0.00),
('1315077942', 'Glazed Hedbergite', '17441', 0.00),
('1315077942', 'Vitric Hedbergite', '17440', 0.00),
('1315077942', 'Hemorphite', '1231', 0.00),
('1315077942', 'Vivid Hemorphite', '17444', 0.00),
('1315077942', 'Radiant Hemorphite', '17445', 0.00),
('1315077942', 'Jaspet', '1226', 0.00),
('1315077942', 'Pure Jaspet', '17448', 0.00),
('1315077942', 'Pristine Jaspet', '17449', 0.00),
('1315077942', 'Kernite', '20', 0.00),
('1315077942', 'Luminous Kernite', '17452', 0.00),
('1315077942', 'Fiery Kernite', '17453', 0.00),
('1315077942', 'Mercoxit', '11396', 0.00),
('1315077942', 'Magma Mercoxit', '17869', 0.00),
('1315077942', 'Vitreous Mercoxit', '17870', 0.00),
('1315077942', 'Omber', '1227', 0.00),
('1315077942', 'Silvery Omber', '17867', 0.00),
('1315077942', 'Golden Omber', '17868', 0.00),
('1315077942', 'Spodumain', '19', 0.00),
('1315077942', 'Bright Spodumain', '17466', 0.00),
('1315077942', 'Gleaming Spodumain', '17467', 0.00),
('1315077942', 'Plagioclase', '18', 0.00),
('1315077942', 'Azure Plagioclase', '17455', 0.00),
('1315077942', 'Rich Plagioclase', '17456', 0.00),
('1315077942', 'Pyroxeres', '1224', 0.00),
('1315077942', 'Solid Pyroxeres', '17459', 0.00),
('1315077942', 'Viscous Pyroxeres', '17460', 0.00),
('1315077942', 'Scordite', '1228', 0.00),
('1315077942', 'Condensed Scordite', '17463', 0.00),
('1315077942', 'Massive Scordite', '17464', 0.00),
('1315077942', 'Veldspar', '1230', 0.00),
('1315077942', 'Concentrated Veldspar', '17470', 0.00),
('1315077942', 'Dense Veldspar', '17471', 0.00),
('1315077942', 'Blue Ice', '16264', 0.00),
('1315077942', 'Clear Icicle', '16262', 0.00),
('1315077942', 'Dark Glitter', '16267', 0.00),
('1315077942', 'Enriched Clear Icicle', '17978', 0.00),
('1315077942', 'Gelidus', '16268', 0.00),
('1315077942', 'Glacial Mass', '16263', 0.00),
('1315077942', 'Glare Crust', '16266', 0.00),
('1315077942', 'Krystallos', '16269', 0.00),
('1315077942', 'Pristine White Glaze', '17976', 0.00),
('1315077942', 'Smooth Glacial Mass', '17977', 0.00),
('1315077942', 'Thick Blue Ice', '17975', 0.00),
('1315077942', 'White Glaze', '16265', 0.00),
('1315077942', 'Condensed Alloy', '11739', 0.00),
('1315077942', 'Crystal Compound', '11741', 0.00),
('1315077942', 'Precious Alloy', '11737', 0.00),
('1315077942', 'Sheen Compound', '11732', 0.00),
('1315077942', 'Gleaming Alloy', '11740', 0.00),
('1315077942', 'Lucent Compound', '11738', 0.00),
('1315077942', 'Dark Compound', '11735', 0.00),
('1315077942', 'Motley Compound', '11733', 0.00),
('1315077942', 'Lustering Alloy', '11736', 0.00),
('1315077942', 'Glossy Compound', '11724', 0.00),
('1315077942', 'Plush Compound', '11725', 0.00),
('1315077942', 'Opulent Compound', '11734', 0.00),
('1315077942', 'Cartesian Temporal Coordinator', '30024', 0.00),
('1315077942', 'Central System Controller', '30270', 0.00),
('1315077942', 'Defensive Control Node', '30269', 0.00),
('1315077942', 'Electromechanical Hull Sheeting', '30254', 0.00),
('1315077942', 'Emergent Combat Analyzer', '30248', 0.00),
('1315077942', 'Emergent Combat Intelligence', '30271', 0.00),
('1315077942', 'Fused Nanomechanical Engines', '30018', 0.00),
('1315077942', 'Heuristic Selfassemblers', '30022', 0.00),
('1315077942', 'Jump Drive Control Nexus', '30268', 0.00),
('1315077942', 'Melted Nanoribbons', '30259', 0.00),
('1315077942', 'Modified Fluid Router', '30021', 0.00),
('1315077942', 'Neurovisual Input Matrix', '30251', 0.00),
('1315077942', 'Powdered C-540 Graphite', '30019', 0.00),
('1315077942', 'Resonance Calibration Matrix', '30258', 0.00),
('1315077942', 'Thermoelectric Catalysts', '30252', 0.00),
('1315077942', 'Fullerite-C28', '30375', 0.00),
('1315077942', 'Fullerite-C32', '30376', 0.00),
('1315077942', 'Fullerite-C50', '30370', 0.00),
('1315077942', 'Fullerite-C60', '30371', 0.00),
('1315077942', 'Fullerite-C70', '30372', 0.00),
('1315077942', 'Fullerite-C72', '30373', 0.00),
('1315077942', 'Fullerite-C84', '30374', 0.00),
('1315077942', 'Fullerite-C320', '30377', 0.00),
('1315077942', 'Fullerite-C540', '30378', 0.00),
('1315077942', 'Neural Network Analyzer', '30744', 0.00),
('1315077942', 'Sleeper Data Library', '30745', 0.00),
('1315077942', 'Ancient Coordinates Database', '30746', 0.00),
('1315077942', 'Sleeper Drone AI Nexus', '30747', 0.00);");
	
echo "Updating the config version number!";
	echo "<br>";
	$configupgrade7 = mysql_query("UPDATE config SET value='$SQLVER' WHERE name='version'");
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=\"index.php\">Click here to Login</a>";
	
}else{
	echo "This is the $VERSION SQL upgrade page.<br>";
	echo "When you are ready to upgrade your Database for $sitename to the newest version click the link below<br>";
	echo "<a href=\"index.php?upgrade=1\"><h1>Upgrade Now to Database Version $SQLVER</h1> (from version " . ($SQLVER - 1) . ")</a><br>";
}
?>

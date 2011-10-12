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
	
		echo "Upgrading Config Table!";
	echo "<br>";
	$configupgrade0 = mysql_query("INSERT INTO `config` (`name`, `value`) VALUES
				('AssaultShipEnabled', '0'), ('BattlecruiserEnabled', '0'), ('BattleshipEnabled', '0'), ('CarrierEnabled', '0'),
				('CommandShipEnabled', '0'), ('CovertOpsEnabled', '0'), ('CruiserEnabled', '0'), ('DestroyerEnabled', '0'),
				('DreadnoughtEnabled', '0'), ('ExhumerEnabled', '0'), ('FreighterEnabled', '0'), ('FrigateEnabled', '0'),
				('HeavyAssaultShipEnabled', '0'), ('IndustrialEnabled', '0'), ('InterceptorEnabled', '0'), ('InterdictorEnabled', '0'),
				('LogisticsShipEnabled', '0'), ('MiningBargeEnabled', '0'), ('ReconShipEnabled', '0'), ('ShuttleEnabled', '0'),
				('TransportShipEnabled', '0'), ('CapitalIndustrialShipEnabled', '0');");
	
	echo "Adding shipvalues Table!";
	echo "<br>";
	$configupgrade1 = mysql_query("
		  CREATE TABLE IF NOT EXISTS `shipvalues` (
		   `id` int(5) NOT NULL auto_increment,
           `modifier` int(5) NOT NULL,
           `time` int(15) NOT NULL,
		   `AssaultShipValue` decimal(5,4) NOT NULL default '1.000',
		   `BattlecruiserValue` decimal(5,4) NOT NULL default '1.000',
		   `BattleshipValue` decimal(5,4) NOT NULL default '1.000',
		   `CarrierValue` decimal(5,4) NOT NULL default '1.000',
		   `CommandShipValue` decimal(5,4) NOT NULL default '1.000',
		   `CovertOpsValue` decimal(5,4) NOT NULL default '1.000',
		   `CruiserValue` decimal(5,4) NOT NULL default '1.000',
		   `DestroyerValue` decimal(5,4) NOT NULL default '1.000',
		   `DreadnoughtValue` decimal(5,4) NOT NULL default '1.000',
		   `ExhumerValue` decimal(5,4) NOT NULL default '1.000',
		   `FreighterValue` decimal(5,4) NOT NULL default '1.000',
		   `FrigateValue` decimal(5,4) NOT NULL default '1.000',
		   `HeavyAssaultShipValue` decimal(5,4) NOT NULL default '1.000',
		   `IndustrialShipValue` decimal(5,4) NOT NULL default '1.000',
		   `InterceptorValue` decimal(5,4) NOT NULL default '1.000',
		   `InterdictorValue` decimal(5,4) NOT NULL default '1.000',
		   `LogisticsShipValue` decimal(5,4) NOT NULL default '1.000',
		   `MiningBargeValue` decimal(5,4) NOT NULL default '1.000',
		   `ReconShipValue` decimal(5,4) NOT NULL default '1.000',
		   `ShuttleValue` decimal(5,4) NOT NULL default '1.000',
		   `TransportShipValue` decimal(5,4) NOT NULL default '1.000',
		   `CapitalIndustrialShipValue` decimal(5,4) NOT NULL default '1.000',
		  PRIMARY KEY  (`id`)
  		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
	");

	echo "Upgrading Hauled Table!";
	echo "<br>";
	$configupgrade3 = mysql_query("ALTER TABLE `hauled` CHANGE COLUMN `Arkonor` `Arkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrimsonArkonor` `CrimsonArkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrimeArkonor` `PrimeArkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Bistot` `Bistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MonoclinicBistot` `MonoclinicBistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `TriclinicBistot` `TriclinicBistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Crokite` `Crokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystallineCrokite` `CrystallineCrokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SharpCrokite` `SharpCrokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkOchre` `DarkOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ObsidianOchre` `ObsidianOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OnyxOchre` `OnyxOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Gneiss` `Gneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `IridescentGneiss` `IridescentGneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrismaticGneiss` `PrismaticGneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Hedbergite` `Hedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlazedHedbergite` `GlazedHedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitricHedbergite` `VitricHedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Hemorphite` `Hemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VividHemorphite` `VividHemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RadiantHemorphite` `RadiantHemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Jaspet` `Jaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PureJaspet` `PureJaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineJaspet` `PristineJaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Kernite` `Kernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FieryKernite` `FieryKernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LuminousKernite` `LuminousKernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Mercoxit` `Mercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MagmaMercoxit` `MagmaMercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitreousMercoxit` `VitreousMercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BrightSpodumain` `BrightSpodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingSpodumain` `GleamingSpodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Spodumain` `Spodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Plagioclase` `Plagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AzurePlagioclase` `AzurePlagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RichPlagioclase` `RichPlagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Omber` `Omber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SilveryOmber` `SilveryOmber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GoldenOmber` `GoldenOmber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Pyroxeres` `Pyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SolidPyroxeres` `SolidPyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ViscousPyroxeres` `ViscousPyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Scordite` `Scordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedScordite` `CondensedScordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MassiveScordite` `MassiveScordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Veldspar` `Veldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ConcentratedVeldspar` `ConcentratedVeldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DenseVeldspar` `DenseVeldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BlueIce` `BlueIce` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ClearIcicle` `ClearIcicle` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkGlitter` `DarkGlitter` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EnrichedClearIcicle` `EnrichedClearIcicle` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Gelidus` `Gelidus` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlacialMass` `GlacialMass` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlareCrust` `GlareCrust` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Krystallos` `Krystallos` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineWhiteGlaze` `PristineWhiteGlaze` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SmoothGlacialMass` `SmoothGlacialMass` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThickBlueIce` `ThickBlueIce` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `WhiteGlaze` `WhiteGlaze` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedAlloy` `CondensedAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystalCompound` `CrystalCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PreciousAlloy` `PreciousAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SheenCompound` `SheenCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingAlloy` `GleamingAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LucentCompound` `LucentCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkCompound` `DarkCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MotleyCompound` `MotleyCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LusteringAlloy` `LusteringAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlossyCompound` `GlossyCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PlushCompound` `PlushCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OpulentCompound` `OpulentCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CartesianTemporalCoordinator` `CartesianTemporalCoordinator` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CentralSystemController` `CentralSystemController` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DefensiveControlNode` `DefensiveControlNode` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ElectromechanicalHullSheeting` `ElectromechanicalHullSheeting` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatAnalyzer` `EmergentCombatAnalyzer` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatIntelligence` `EmergentCombatIntelligence` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FusedNanomechanicalEngines` `FusedNanomechanicalEngines` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `HeuristicSelfassemblers` `HeuristicSelfassemblers` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `JumpDriveControlNexus` `JumpDriveControlNexus` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MeltedNanoribbons` `MeltedNanoribbons` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ModifiedFluidRouter` `ModifiedFluidRouter` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeurovisualInputMatrix` `NeurovisualInputMatrix` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PowderedC540Graphite` `PowderedC540Graphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ResonanceCalibrationMatrix` `ResonanceCalibrationMatrix` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThermoelectricCatalysts` `ThermoelectricCatalysts` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC28` `FulleriteC28` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC32` `FulleriteC32` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC50` `FulleriteC50` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC60` `FulleriteC60` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC70` `FulleriteC70` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC72` `FulleriteC72` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC84` `FulleriteC84` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC320` `FulleriteC320` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC540` `FulleriteC540` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeuralNetworkAnalyzer` `NeuralNetworkAnalyzer` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDataLibrary` `SleeperDataLibrary` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AncientCoordinatesDatabase` `AncientCoordinatesDatabase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDroneAINexus` `SleeperDroneAINexus` BIGINT(255) NOT NULL DEFAULT '0';");
	
	echo "Upgrading Events Table!";
	echo "<br>";
	$configupgrade4 = mysql_query("
		  ALTER TABLE `events` CHANGE COLUMN `collateral` `collateral` BIGINT(255) NOT NULL DEFAULT '0';");
	
	echo "Upgrading runs Table!";
	echo "<br>";
	$configupgrade5 = mysql_query("ALTER TABLE `runs` CHANGE COLUMN `Arkonor` `Arkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrimsonArkonor` `CrimsonArkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrimeArkonor` `PrimeArkonor` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Bistot` `Bistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MonoclinicBistot` `MonoclinicBistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `TriclinicBistot` `TriclinicBistot` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Crokite` `Crokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystallineCrokite` `CrystallineCrokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SharpCrokite` `SharpCrokite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkOchre` `DarkOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ObsidianOchre` `ObsidianOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OnyxOchre` `OnyxOchre` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Gneiss` `Gneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `IridescentGneiss` `IridescentGneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrismaticGneiss` `PrismaticGneiss` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Omber` `Omber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SilveryOmber` `SilveryOmber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GoldenOmber` `GoldenOmber` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Hedbergite` `Hedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlazedHedbergite` `GlazedHedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitricHedbergite` `VitricHedbergite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Hemorphite` `Hemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VividHemorphite` `VividHemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RadiantHemorphite` `RadiantHemorphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Jaspet` `Jaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PureJaspet` `PureJaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineJaspet` `PristineJaspet` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Kernite` `Kernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FieryKernite` `FieryKernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LuminousKernite` `LuminousKernite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Mercoxit` `Mercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MagmaMercoxit` `MagmaMercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitreousMercoxit` `VitreousMercoxit` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BrightSpodumain` `BrightSpodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingSpodumain` `GleamingSpodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Spodumain` `Spodumain` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Plagioclase` `Plagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AzurePlagioclase` `AzurePlagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RichPlagioclase` `RichPlagioclase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Pyroxeres` `Pyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SolidPyroxeres` `SolidPyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ViscousPyroxeres` `ViscousPyroxeres` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Scordite` `Scordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedScordite` `CondensedScordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MassiveScordite` `MassiveScordite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Veldspar` `Veldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ConcentratedVeldspar` `ConcentratedVeldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DenseVeldspar` `DenseVeldspar` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BlueIce` `BlueIce` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ClearIcicle` `ClearIcicle` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkGlitter` `DarkGlitter` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EnrichedClearIcicle` `EnrichedClearIcicle` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Gelidus` `Gelidus` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlacialMass` `GlacialMass` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlareCrust` `GlareCrust` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `Krystallos` `Krystallos` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineWhiteGlaze` `PristineWhiteGlaze` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SmoothGlacialMass` `SmoothGlacialMass` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThickBlueIce` `ThickBlueIce` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `WhiteGlaze` `WhiteGlaze` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ArkonorWanted` `ArkonorWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrimsonArkonorWanted` `CrimsonArkonorWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrimeArkonorWanted` `PrimeArkonorWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BistotWanted` `BistotWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MonoclinicBistotWanted` `MonoclinicBistotWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `TriclinicBistotWanted` `TriclinicBistotWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrokiteWanted` `CrokiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystallineCrokiteWanted` `CrystallineCrokiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SharpCrokiteWanted` `SharpCrokiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkOchreWanted` `DarkOchreWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ObsidianOchreWanted` `ObsidianOchreWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OnyxOchreWanted` `OnyxOchreWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GneissWanted` `GneissWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `IridescentGneissWanted` `IridescentGneissWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PrismaticGneissWanted` `PrismaticGneissWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `HedbergiteWanted` `HedbergiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlazedHedbergiteWanted` `GlazedHedbergiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitricHedbergiteWanted` `VitricHedbergiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `HemorphiteWanted` `HemorphiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VividHemorphiteWanted` `VividHemorphiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RadiantHemorphiteWanted` `RadiantHemorphiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `JaspetWanted` `JaspetWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PureJaspetWanted` `PureJaspetWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineJaspetWanted` `PristineJaspetWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `KerniteWanted` `KerniteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FieryKerniteWanted` `FieryKerniteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LuminousKerniteWanted` `LuminousKerniteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MercoxitWanted` `MercoxitWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MagmaMercoxitWanted` `MagmaMercoxitWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VitreousMercoxitWanted` `VitreousMercoxitWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BrightSpodumainWanted` `BrightSpodumainWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingSpodumainWanted` `GleamingSpodumainWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SpodumainWanted` `SpodumainWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PlagioclaseWanted` `PlagioclaseWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AzurePlagioclaseWanted` `AzurePlagioclaseWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `RichPlagioclaseWanted` `RichPlagioclaseWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PyroxeresWanted` `PyroxeresWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OmberWanted` `OmberWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SilveryOmberWanted` `SilveryOmberWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GoldenOmberWanted` `GoldenOmberWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SolidPyroxeresWanted` `SolidPyroxeresWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ViscousPyroxeresWanted` `ViscousPyroxeresWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ScorditeWanted` `ScorditeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedScorditeWanted` `CondensedScorditeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MassiveScorditeWanted` `MassiveScorditeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `VeldsparWanted` `VeldsparWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ConcentratedVeldsparWanted` `ConcentratedVeldsparWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DenseVeldsparWanted` `DenseVeldsparWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `BlueIceWanted` `BlueIceWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ClearIcicleWanted` `ClearIcicleWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkGlitterWanted` `DarkGlitterWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EnrichedClearIcicleWanted` `EnrichedClearIcicleWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GelidusWanted` `GelidusWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlacialMassWanted` `GlacialMassWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlareCrustWanted` `GlareCrustWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `KrystallosWanted` `KrystallosWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PristineWhiteGlazeWanted` `PristineWhiteGlazeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SmoothGlacialMassWanted` `SmoothGlacialMassWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThickBlueIceWanted` `ThickBlueIceWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `WhiteGlazeWanted` `WhiteGlazeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedAlloy` `CondensedAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystalCompound` `CrystalCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PreciousAlloy` `PreciousAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SheenCompound` `SheenCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingAlloy` `GleamingAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LucentCompound` `LucentCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkCompound` `DarkCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MotleyCompound` `MotleyCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LusteringAlloy` `LusteringAlloy` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlossyCompound` `GlossyCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PlushCompound` `PlushCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OpulentCompound` `OpulentCompound` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CartesianTemporalCoordinator` `CartesianTemporalCoordinator` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CentralSystemController` `CentralSystemController` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DefensiveControlNode` `DefensiveControlNode` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ElectromechanicalHullSheeting` `ElectromechanicalHullSheeting` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatAnalyzer` `EmergentCombatAnalyzer` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatIntelligence` `EmergentCombatIntelligence` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FusedNanomechanicalEngines` `FusedNanomechanicalEngines` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `HeuristicSelfassemblers` `HeuristicSelfassemblers` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `JumpDriveControlNexus` `JumpDriveControlNexus` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MeltedNanoribbons` `MeltedNanoribbons` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ModifiedFluidRouter` `ModifiedFluidRouter` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeurovisualInputMatrix` `NeurovisualInputMatrix` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PowderedC540Graphite` `PowderedC540Graphite` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ResonanceCalibrationMatrix` `ResonanceCalibrationMatrix` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThermoelectricCatalysts` `ThermoelectricCatalysts` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC28` `FulleriteC28` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC32` `FulleriteC32` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC50` `FulleriteC50` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC60` `FulleriteC60` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC70` `FulleriteC70` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC72` `FulleriteC72` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC84` `FulleriteC84` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC320` `FulleriteC320` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC540` `FulleriteC540` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CondensedAlloyWanted` `CondensedAlloyWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CrystalCompoundWanted` `CrystalCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PreciousAlloyWanted` `PreciousAlloyWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SheenCompoundWanted` `SheenCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GleamingAlloyWanted` `GleamingAlloyWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LucentCompoundWanted` `LucentCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DarkCompoundWanted` `DarkCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MotleyCompoundWanted` `MotleyCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `LusteringAlloyWanted` `LusteringAlloyWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `GlossyCompoundWanted` `GlossyCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PlushCompoundWanted` `PlushCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `OpulentCompoundWanted` `OpulentCompoundWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CartesianTemporalCoordinatorWanted` `CartesianTemporalCoordinatorWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `CentralSystemControllerWanted` `CentralSystemControllerWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `DefensiveControlNodeWanted` `DefensiveControlNodeWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ElectromechanicalHullSheetingWanted` `ElectromechanicalHullSheetingWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatAnalyzerWanted` `EmergentCombatAnalyzerWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `EmergentCombatIntelligenceWanted` `EmergentCombatIntelligenceWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FusedNanomechanicalEnginesWanted` `FusedNanomechanicalEnginesWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `HeuristicSelfassemblersWanted` `HeuristicSelfassemblersWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `JumpDriveControlNexusWanted` `JumpDriveControlNexusWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `MeltedNanoribbonsWanted` `MeltedNanoribbonsWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ModifiedFluidRouterWanted` `ModifiedFluidRouterWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeurovisualInputMatrixWanted` `NeurovisualInputMatrixWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `PowderedC540GraphiteWanted` `PowderedC540GraphiteWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ResonanceCalibrationMatrixWanted` `ResonanceCalibrationMatrixWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ThermoelectricCatalystsWanted` `ThermoelectricCatalystsWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC28Wanted` `FulleriteC28Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC32Wanted` `FulleriteC32Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC50Wanted` `FulleriteC50Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC60Wanted` `FulleriteC60Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC70Wanted` `FulleriteC70Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC72Wanted` `FulleriteC72Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC84Wanted` `FulleriteC84Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC320Wanted` `FulleriteC320Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `FulleriteC540Wanted` `FulleriteC540Wanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeuralNetworkAnalyzer` `NeuralNetworkAnalyzer` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDataLibrary` `SleeperDataLibrary` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AncientCoordinatesDatabase` `AncientCoordinatesDatabase` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDroneAINexus` `SleeperDroneAINexus` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `NeuralNetworkAnalyzerWanted` `NeuralNetworkAnalyzerWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDataLibraryWanted` `SleeperDataLibraryWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `AncientCoordinatesDatabaseWanted` `AncientCoordinatesDatabaseWanted` BIGINT(255) NOT NULL DEFAULT '0'  , CHANGE COLUMN `SleeperDroneAINexusWanted` `SleeperDroneAINexusWanted` BIGINT(255) NOT NULL DEFAULT '0';");
	$configupgrade6 = mysql_query("ALTER TABLE `runs`  ADD `shipGlue` INT(4) NOT NULL DEFAULT '0' AFTER `oreGlue`;");

	echo "Upgrading payoutRequests Table!";
	echo "<br>";
	$configupgrade7 = mysql_query("ALTER TABLE `payoutRequests` CHANGE COLUMN `amount` `amount` BIGINT(255) NOT NULL;");
	
	echo "Upgrading config Table!";
	echo "<br>";
	$configupgrade8 = mysql_query("UPDATE `config` SET `name`='FulleriteC540Enabled' WHERE `name`='FilleriteC540Enabled';");
	
	echo "Creating m3values Table!";
	echo "<br>";
	$configupgrade9 = mysql_query("CREATE TABLE `m3values` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ArkonorM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `CrimsonArkonorM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `PrimeArkonorM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `BistotM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `MonoclinicBistotM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `TriclinicBistotM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `CrokiteM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `CrystallineCrokiteM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `SharpCrokiteM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `DarkOchreM3` decimal(6,2) NOT NULL DEFAULT '8.00',
  `ObsidianOchreM3` decimal(6,2) NOT NULL DEFAULT '8.00',
  `OnyxOchreM3` decimal(6,2) NOT NULL DEFAULT '8.00',
  `GneissM3` decimal(6,2) NOT NULL DEFAULT '5.00',
  `IridescentGneissM3` decimal(6,2) NOT NULL DEFAULT '5.00',
  `PrismaticGneissM3` decimal(6,2) NOT NULL DEFAULT '5.00',
  `HedbergiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `GlazedHedbergiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `VitricHedbergiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `HemorphiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `VividHemorphiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `RadiantHemorphiteM3` decimal(6,2) NOT NULL DEFAULT '3.00',
  `JaspetM3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `PureJaspetM3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `PristineJaspetM3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `KerniteM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `FieryKerniteM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `LuminousKerniteM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `MercoxitM3` decimal(6,2) NOT NULL DEFAULT '40.00',
  `MagmaMercoxitM3` decimal(6,2) NOT NULL DEFAULT '40.00',
  `VitreousMercoxitM3` decimal(6,2) NOT NULL DEFAULT '40.00',
  `BrightSpodumainM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `GleamingSpodumainM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `SpodumainM3` decimal(6,2) NOT NULL DEFAULT '16.00',
  `OmberM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `SilveryOmberM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `GoldenOmberM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `PlagioclaseM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `AzurePlagioclaseM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `RichPlagioclaseM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `PyroxeresM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `SolidPyroxeresM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ViscousPyroxeresM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ScorditeM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `CondensedScorditeM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `MassiveScorditeM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `VeldsparM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ConcentratedVeldsparM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `DenseVeldsparM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `BlueIceM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `ClearIcicleM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `DarkGlitterM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `EnrichedClearIcicleM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `GelidusM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `GlacialMassM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `GlareCrustM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `KrystallosM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `PristineWhiteGlazeM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `SmoothGlacialMassM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `ThickBlueIceM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `WhiteGlazeM3` decimal(6,2) NOT NULL DEFAULT '1000.00',
  `CondensedAlloyM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `CrystalCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `PreciousAlloyM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `SheenCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `GleamingAlloyM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `LucentCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `DarkCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `MotleyCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `LusteringAlloyM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `GlossyCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `PlushCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `OpulentCompoundM3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `CartesianTemporalCoordinatorM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `CentralSystemControllerM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `DefensiveControlNodeM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ElectromechanicalHullSheetingM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `EmergentCombatAnalyzerM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `EmergentCombatIntelligenceM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `FusedNanomechanicalEnginesM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `HeuristicSelfassemblersM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `JumpDriveControlNexusM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `MeltedNanoribbonsM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ModifiedFluidRouterM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `NeurovisualInputMatrixM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `PowderedC540GraphiteM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ResonanceCalibrationMatrixM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `ThermoelectricCatalystsM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `FulleriteC28M3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `FulleriteC32M3` decimal(6,2) NOT NULL DEFAULT '5.00',
  `FulleriteC50M3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `FulleriteC60M3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `FulleriteC70M3` decimal(6,2) NOT NULL DEFAULT '1.00',
  `FulleriteC72M3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `FulleriteC84M3` decimal(6,2) NOT NULL DEFAULT '2.00',
  `FulleriteC320M3` decimal(6,2) NOT NULL DEFAULT '5.00',
  `FulleriteC540M3` decimal(6,2) NOT NULL DEFAULT '10.00',
  `NeuralNetworkAnalyzerM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `SleeperDataLibraryM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `AncientCoordinatesDatabaseM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `SleeperDroneAINexusM3` decimal(6,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");

	echo "Populating m3value Table!";
	echo "<br>";
	$configupgrade10 = mysql_query("INSERT INTO `m3values` (id,ArkonorM3,CrimsonArkonorM3,PrimeArkonorM3,BistotM3,MonoclinicBistotM3,TriclinicBistotM3,CrokiteM3,CrystallineCrokiteM3,SharpCrokiteM3,DarkOchreM3,ObsidianOchreM3,OnyxOchreM3,GneissM3,IridescentGneissM3,PrismaticGneissM3,HedbergiteM3,GlazedHedbergiteM3,VitricHedbergiteM3,HemorphiteM3,VividHemorphiteM3,RadiantHemorphiteM3,JaspetM3,PureJaspetM3,PristineJaspetM3,KerniteM3,FieryKerniteM3,LuminousKerniteM3,MercoxitM3,MagmaMercoxitM3,VitreousMercoxitM3,BrightSpodumainM3,GleamingSpodumainM3,SpodumainM3,OmberM3,SilveryOmberM3,GoldenOmberM3,PlagioclaseM3,AzurePlagioclaseM3,RichPlagioclaseM3,PyroxeresM3,SolidPyroxeresM3,ViscousPyroxeresM3,ScorditeM3,CondensedScorditeM3,MassiveScorditeM3,VeldsparM3,ConcentratedVeldsparM3,DenseVeldsparM3,BlueIceM3,ClearIcicleM3,DarkGlitterM3,EnrichedClearIcicleM3,GelidusM3,GlacialMassM3,GlareCrustM3,KrystallosM3,PristineWhiteGlazeM3,SmoothGlacialMassM3,ThickBlueIceM3,WhiteGlazeM3,CondensedAlloyM3,CrystalCompoundM3,PreciousAlloyM3,SheenCompoundM3,GleamingAlloyM3,LucentCompoundM3,DarkCompoundM3,MotleyCompoundM3,LusteringAlloyM3,GlossyCompoundM3,PlushCompoundM3,OpulentCompoundM3,CartesianTemporalCoordinatorM3,CentralSystemControllerM3,DefensiveControlNodeM3,ElectromechanicalHullSheetingM3,EmergentCombatAnalyzerM3,EmergentCombatIntelligenceM3,FusedNanomechanicalEnginesM3,HeuristicSelfassemblersM3,JumpDriveControlNexusM3,MeltedNanoribbonsM3,ModifiedFluidRouterM3,NeurovisualInputMatrixM3,PowderedC540GraphiteM3,ResonanceCalibrationMatrixM3,ThermoelectricCatalystsM3,FulleriteC28M3,FulleriteC32M3,FulleriteC50M3,FulleriteC60M3,FulleriteC70M3,FulleriteC72M3,FulleriteC84M3,FulleriteC320M3,FulleriteC540M3,NeuralNetworkAnalyzerM3,SleeperDataLibraryM3,AncientCoordinatesDatabaseM3,SleeperDroneAINexusM3) VALUES (1,'16.00','16.00','16.00','16.00','16.00','16.00','16.00','16.00','16.00','8.00','8.00','8.00','5.00','5.00','5.00','3.00','3.00','3.00','3.00','3.00','3.00','2.00','2.00','2.00','1.20','1.20','1.20','40.00','40.00','40.00','16.00','16.00','16.00','0.60','0.60','0.60','0.35','0.35','0.35','0.30','0.30','0.30','0.15','0.15','0.15','0.10','0.10','0.10','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1000.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','1.00','2.00','5.00','1.00','1.00','1.00','2.00','2.00','5.00','10.00','0.01','0.01','0.01','0.01');
");
	
	echo "Add Configuration for Using Eve-Central Market Values";
	echo "<br>";
	$configupgrade11 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('useMarket','1');");
	$configupgrade12 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('useRegion','10000002');");
	$configupgrade13 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('orderType','0');");
	$configupgrade14 = mysql_query("INSERT INTO `config`(`name`, `value`) VALUES ('priceCriteria','2');");
	
	echo "Create Market Cache Table";
	echo "<br>";
	$configupgrade15 = mysql_query("CREATE TABLE IF NOT EXISTS `itemList` (`updateTime` varchar(10) DEFAULT NULL, `itemName` varchar(31) DEFAULT NULL, `itemID` varchar(10) DEFAULT NULL, `value` decimal(16,2) DEFAULT '0.00') ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	
	echo "Add Items into Market Cache Table";
	echo "<br>";
	$configupgrade16 = mysql_query("INSERT INTO `itemList` (`updateTime`, `itemName`, `itemID`, `value`) VALUES ('1315077942', 'Arkonor', '22', 1),('1315077942', 'Crimson Arkonor', '17425', 1),('1315077942', 'Prime Arkonor', '17426', 1),('1315077942', 'Bistot', '1223', 1),('1315077942', 'Triclinic Bistot', '17428', 1),('1315077942', 'Monoclinic Bistot', '17429', 1),('1315077942', 'Crokite', '1225', 1),('1315077942', 'Sharp Crokite', '17432', 1),('1315077942', 'Crystalline Crokite', '17433', 1),('1315077942', 'Dark Ochre', '1232', 1),('1315077942', 'Onyx Ochre', '17436', 1),('1315077942', 'Obsidian Ochre', '17437', 1),('1315077942', 'Gneiss', '1229', 1),('1315077942', 'Iridescent Gneiss', '17865', 1),('1315077942', 'Prismatic Gneiss', '17866', 1),('1315077942', 'Hedbergite', '21', 1),('1315077942', 'Glazed Hedbergite', '17441', 1),('1315077942', 'Vitric Hedbergite', '17440', 1),('1315077942', 'Hemorphite', '1231', 1),('1315077942', 'Vivid Hemorphite', '17444', 1),('1315077942', 'Radiant Hemorphite', '17445', 1),('1315077942', 'Jaspet', '1226', 1),('1315077942', 'Pure Jaspet', '17448', 1),('1315077942', 'Pristine Jaspet', '17449', 1),('1315077942', 'Kernite', '20', 1),('1315077942', 'Luminous Kernite', '17452', 1),('1315077942', 'Fiery Kernite', '17453', 1),('1315077942', 'Mercoxit', '11396', 1),('1315077942', 'Magma Mercoxit', '17869', 1),('1315077942', 'Vitreous Mercoxit', '17870', 1),('1315077942', 'Omber', '1227', 1),('1315077942', 'Silvery Omber', '17867', 1),('1315077942', 'Golden Omber', '17868', 1),('1315077942', 'Spodumain', '19', 1),('1315077942', 'Bright Spodumain', '17466', 1),('1315077942', 'Gleaming Spodumain', '17467', 1),('1315077942', 'Plagioclase', '18', 1),('1315077942', 'Azure Plagioclase', '17455', 1),('1315077942', 'Rich Plagioclase', '17456', 1),('1315077942', 'Pyroxeres', '1224', 1),('1315077942', 'Solid Pyroxeres', '17459', 1),('1315077942', 'Viscous Pyroxeres', '17460', 1),('1315077942', 'Scordite', '1228', 1),('1315077942', 'Condensed Scordite', '17463', 1),('1315077942', 'Massive Scordite', '17464', 1),('1315077942', 'Veldspar', '1230', 1),('1315077942', 'Concentrated Veldspar', '17470', 1),('1315077942', 'Dense Veldspar', '17471', 1),('1315077942', 'Blue Ice', '16264', 1),('1315077942', 'Clear Icicle', '16262', 1),('1315077942', 'Dark Glitter', '16267', 1),('1315077942', 'Enriched Clear Icicle', '17978', 1),('1315077942', 'Gelidus', '16268', 1),('1315077942', 'Glacial Mass', '16263', 1),('1315077942', 'Glare Crust', '16266', 1),('1315077942', 'Krystallos', '16269', 1),('1315077942', 'Pristine White Glaze', '17976', 1),('1315077942', 'Smooth Glacial Mass', '17977', 1),('1315077942', 'Thick Blue Ice', '17975', 1),('1315077942', 'White Glaze', '16265', 1),('1315077942', 'Condensed Alloy', '11739', 1),('1315077942', 'Crystal Compound', '11741', 1),('1315077942', 'Precious Alloy', '11737', 1),('1315077942', 'Sheen Compound', '11732', 1),('1315077942', 'Gleaming Alloy', '11740', 1),('1315077942', 'Lucent Compound', '11738', 1),('1315077942', 'Dark Compound', '11735', 1),('1315077942', 'Motley Compound', '11733', 1),('1315077942', 'Lustering Alloy', '11736', 1),('1315077942', 'Glossy Compound', '11724', 1),('1315077942', 'Plush Compound', '11725', 1),('1315077942', 'Opulent Compound', '11734', 1),('1315077942', 'Cartesian Temporal Coordinator', '30024', 1),('1315077942', 'Central System Controller', '30270', 1),('1315077942', 'Defensive Control Node', '30269', 1),('1315077942', 'Electromechanical Hull Sheeting', '30254', 1),('1315077942', 'Emergent Combat Analyzer', '30248', 1),('1315077942', 'Emergent Combat Intelligence', '30271', 1),('1315077942', 'Fused Nanomechanical Engines', '30018', 1),('1315077942', 'Heuristic Selfassemblers', '30022', 1),('1315077942', 'Jump Drive Control Nexus', '30268', 1),('1315077942', 'Melted Nanoribbons', '30259', 1),('1315077942', 'Modified Fluid Router', '30021', 1),('1315077942', 'Neurovisual Input Matrix', '30251', 1),('1315077942', 'Powdered C-540 Graphite', '30019', 1),('1315077942', 'Resonance Calibration Matrix', '30258', 1),('1315077942', 'Thermoelectric Catalysts', '30252', 1),('1315077942', 'Fullerite-C28', '30375', 1),('1315077942', 'Fullerite-C32', '30376', 1),('1315077942', 'Fullerite-C50', '30370', 1),('1315077942', 'Fullerite-C60', '30371', 1),('1315077942', 'Fullerite-C70', '30372', 1),('1315077942', 'Fullerite-C72', '30373', 1),('1315077942', 'Fullerite-C84', '30374', 1),('1315077942', 'Fullerite-C320', '30377', 1),('1315077942', 'Fullerite-C540', '30378', 1),('1315077942', 'Neural Network Analyzer', '30744', 1),('1315077942', 'Sleeper Data Library', '30745', 1),('1315077942', 'Ancient Coordinates Database', '30746', 1),('1315077942', 'Sleeper Drone AI Nexus', '30747', 1);");



echo "Updating the config version number!";
	echo "<br>";
	$configupgrade17 = mysql_query("UPDATE config SET value='23' WHERE name='version'");
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=http://".$site.">Click here to Login</a>";
	
}else{

?>
<center>
This is the Mining Buddy SQL upgrade page.<br>
When you are ready to upgrade you Mining Buddy Install to the newest version click the link below<br>
<a href="mysql-update-20-23.php?upgrade=1">Upgrade Now</a>
<?
}
?>
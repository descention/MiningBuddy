INSERT INTO `config` (`name`, `value`) VALUES ('NeuralNetworkAnalyzerEnabled', '0'), 
('SleeperDataLibraryEnabled', '0'), ('AncientCoordinatesDatabaseEnabled', '0'), ('SleeperDroneAINexusEnabled', '0');

ALTER TABLE  `orevalues` ADD  `NeuralNetworkAnalyzerWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `SleeperDataLibraryWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `AncientCoordinatesDatabaseWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `SleeperDroneAINexusWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';

ALTER TABLE  `hauled` ADD  `NeuralNetworkAnalyzer` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `SleeperDataLibrary` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `AncientCoordinatesDatabase` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `SleeperDroneAINexus` INT(5) NOT NULL DEFAULT '0';

ALTER TABLE  `runs` ADD  `NeuralNetworkAnalyzer` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SleeperDataLibrary` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `AncientCoordinatesDatabase` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SleeperDroneAINexus` INT(6) NOT NULL DEFAULT '0';

ALTER TABLE  `runs` ADD  `NeuralNetworkAnalyzerWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SleeperDataLibraryWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `AncientCoordinatesDatabaseWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SleeperDroneAINexusWanted` INT(6) NOT NULL DEFAULT '0';

ALTER TABLE  `orevalues` CHANGE  `ArkonorWorth`  `ArkonorWorth` DECIMAL( 16, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `orevalues` CHANGE `CrimsonArkonorWorth` `CrimsonArkonorWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PrimeArkonorWorth` `PrimeArkonorWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `BistotWorth` `BistotWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MonoclinicBistotWorth` `MonoclinicBistotWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `TriclinicBistotWorth` `TriclinicBistotWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CrokiteWorth` `CrokiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CrystallineCrokiteWorth` `CrystallineCrokiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SharpCrokiteWorth` `SharpCrokiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `DarkOchreWorth` `DarkOchreWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ObsidianOchreWorth` `ObsidianOchreWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `OnyxOchreWorth` `OnyxOchreWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GneissWorth` `GneissWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `IridescentGneissWorth` `IridescentGneissWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PrismaticGneissWorth` `PrismaticGneissWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `HedbergiteWorth` `HedbergiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GlazedHedbergiteWorth` `GlazedHedbergiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `VitricHedbergiteWorth` `VitricHedbergiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `HemorphiteWorth` `HemorphiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `VividHemorphiteWorth` `VividHemorphiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `RadiantHemorphiteWorth` `RadiantHemorphiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `JaspetWorth` `JaspetWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PureJaspetWorth` `PureJaspetWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PristineJaspetWorth` `PristineJaspetWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `KerniteWorth` `KerniteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FieryKerniteWorth` `FieryKerniteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `LuminousKerniteWorth` `LuminousKerniteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MercoxitWorth` `MercoxitWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MagmaMercoxitWorth` `MagmaMercoxitWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `VitreousMercoxitWorth` `VitreousMercoxitWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `BrightSpodumainWorth` `BrightSpodumainWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GleamingSpodumainWorth` `GleamingSpodumainWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SpodumainWorth` `SpodumainWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `OmberWorth` `OmberWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SilveryOmberWorth` `SilveryOmberWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GoldenOmberWorth` `GoldenOmberWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PlagioclaseWorth` `PlagioclaseWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `AzurePlagioclaseWorth` `AzurePlagioclaseWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `RichPlagioclaseWorth` `RichPlagioclaseWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PyroxeresWorth` `PyroxeresWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SolidPyroxeresWorth` `SolidPyroxeresWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ViscousPyroxeresWorth` `ViscousPyroxeresWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ScorditeWorth` `ScorditeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CondensedScorditeWorth` `CondensedScorditeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MassiveScorditeWorth` `MassiveScorditeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `VeldsparWorth` `VeldsparWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ConcentratedVeldsparWorth` `ConcentratedVeldsparWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `DenseVeldsparWorth` `DenseVeldsparWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `BlueIceWorth` `BlueIceWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ClearIcicleWorth` `ClearIcicleWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `DarkGlitterWorth` `DarkGlitterWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `EnrichedClearIcicleWorth` `EnrichedClearIcicleWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GelidusWorth` `GelidusWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GlacialMassWorth` `GlacialMassWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GlareCrustWorth` `GlareCrustWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `KrystallosWorth` `KrystallosWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PristineWhiteGlazeWorth` `PristineWhiteGlazeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SmoothGlacialMassWorth` `SmoothGlacialMassWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ThickBlueIceWorth` `ThickBlueIceWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `WhiteGlazeWorth` `WhiteGlazeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CondensedAlloyWorth` `CondensedAlloyWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CrystalCompoundWorth` `CrystalCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PreciousAlloyWorth` `PreciousAlloyWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SheenCompoundWorth` `SheenCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GleamingAlloyWorth` `GleamingAlloyWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `LucentCompoundWorth` `LucentCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `DarkCompoundWorth` `DarkCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MotleyCompoundWorth` `MotleyCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `LusteringAlloyWorth` `LusteringAlloyWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `GlossyCompoundWorth` `GlossyCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PlushCompoundWorth` `PlushCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `OpulentCompoundWorth` `OpulentCompoundWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CartesianTemporalCoordinatorWorth` `CartesianTemporalCoordinatorWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `CentralSystemControllerWorth` `CentralSystemControllerWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `DefensiveControlNodeWorth` `DefensiveControlNodeWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ElectromechanicalHullSheetingWorth` `ElectromechanicalHullSheetingWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `EmergentCombatAnalyzerWorth` `EmergentCombatAnalyzerWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `EmergentCombatIntelligenceWorth` `EmergentCombatIntelligenceWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FusedNanomechanicalEnginesWorth` `FusedNanomechanicalEnginesWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `HeuristicSelfassemblersWorth` `HeuristicSelfassemblersWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `JumpDriveControlNexusWorth` `JumpDriveControlNexusWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `MeltedNanoribbonsWorth` `MeltedNanoribbonsWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ModifiedFluidRouterWorth` `ModifiedFluidRouterWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `NeurovisualInputMatrixWorth` `NeurovisualInputMatrixWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `PowderedC540GraphiteWorth` `PowderedC540GraphiteWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ResonanceCalibrationMatrixWorth` `ResonanceCalibrationMatrixWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `ThermoelectricCatalystsWorth` `ThermoelectricCatalystsWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC28Worth` `FulleriteC28Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC32Worth` `FulleriteC32Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC50Worth` `FulleriteC50Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC60Worth` `FulleriteC60Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC70Worth` `FulleriteC70Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC72Worth` `FulleriteC72Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC84Worth` `FulleriteC84Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC320Worth` `FulleriteC320Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `FulleriteC540Worth` `FulleriteC540Worth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `NeuralNetworkAnalyzerWorth` `NeuralNetworkAnalyzerWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SleeperDataLibraryWorth` `SleeperDataLibraryWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `AncientCoordinatesDatabaseWorth` `AncientCoordinatesDatabaseWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00', 
CHANGE `SleeperDroneAINexusWorth` `SleeperDroneAINexusWorth` DECIMAL(16,2) NOT NULL DEFAULT '0.00';

UPDATE config SET value='20' WHERE name='version';
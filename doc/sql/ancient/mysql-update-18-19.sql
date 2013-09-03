INSERT INTO `config` (`name`, `value`) VALUES ('CondensedAlloyEnabled', '0'), 
('CrystalCompoundEnabled', '0'), ('PreciousAlloyEnabled', '0'), ('SheenCompoundEnabled', '0'), 
('GleamingAlloyEnabled', '0'), ('LucentCompoundEnabled', '0'), ('DarkCompoundEnabled', '0'), 
('MotleyCompoundEnabled', '0'), ('LusteringAlloyEnabled', '0'), ('GlossyCompoundEnabled', '0'), 
('PlushhCompoundEnabled', '0'), ('OpulentCompoundEnabled', '0'), 
('CartesianTemporalCoordinatorEnabled', '0'), ('CentralSystemControllerEnabled', '0'), 
('DefensiveControlNodeEnabled', '0'), ('ElectromechanicalHullSheetingEnabled', '0'), 
('EmergentCombatAnalyzerEnabled', '0'), ('EmergentCombatIntelligenceEnabled', '0'), 
('FusedNanomechanicalEnginesEnabled', '0'), ('HeuristicSelfassemblersEnabled', '0'), 
('JumpDriveControlNexusEnabled', '0'), ('MeltedNanoribbonsEnabled', '0'), 
('ModifiedFluidRouterEnabled', '0'), ('NeurovisualInputMatrixEnabled', '0'), 
('PowderedC540GraphiteEnabled', '0'), ('ResonanceCalibrationMatrixEnabled', '0'), 
('ThermoelectricCatalystsEnabled', '0'), ('FulleriteC50Enabled', '0'), 
('FulleriteC60Enabled', '0'), ('FulleriteC70Enabled', '0'), ('FulleriteC72Enabled', '0'), 
('FulleriteC84Enabled', '0'), ('FulleriteC28Enabled', '0'), ('FulleriteC32Enabled', '0'), 
('FulleriteC320Enabled', '0'), ('FilleriteC540Enabled', '0');

ALTER TABLE  `orevalues` ADD  `CondensedAlloyWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `CrystalCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `PreciousAlloyWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `SheenCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `GleamingAlloyWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `LucentCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `DarkCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `MotleyCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `LusteringAlloyWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `GlossyCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `PlushCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `OpulentCompoundWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `CartesianTemporalCoordinatorWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `CentralSystemControllerWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `DefensiveControlNodeWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `ElectromechanicalHullSheetingWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `EmergentCombatAnalyzerWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `EmergentCombatIntelligenceWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FusedNanomechanicalEnginesWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `HeuristicSelfassemblersWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `JumpDriveControlNexusWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `MeltedNanoribbonsWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `ModifiedFluidRouterWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `NeurovisualInputMatrixWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `PowderedC540GraphiteWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `ResonanceCalibrationMatrixWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `ThermoelectricCatalystsWorth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC28Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC32Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC50Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC60Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC70Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC72Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC84Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC320Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE  `orevalues` ADD  `FulleriteC540Worth` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00';

ALTER TABLE  `hauled` ADD  `CondensedAlloy` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `CrystalCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `PreciousAlloy` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `SheenCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `GleamingAlloy` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `LucentCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `DarkCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `MotleyCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `LusteringAlloy` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `GlossyCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `PlushCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `OpulentCompound` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `CartesianTemporalCoordinator` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `CentralSystemController` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `DefensiveControlNode` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `ElectromechanicalHullSheeting` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `EmergentCombatAnalyzer` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `EmergentCombatIntelligence` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FusedNanomechanicalEngines` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `HeuristicSelfassemblers` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `JumpDriveControlNexus` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `MeltedNanoribbons` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `ModifiedFluidRouter` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `NeurovisualInputMatrix` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `PowderedC540Graphite` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `ResonanceCalibrationMatrix` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `ThermoelectricCatalysts` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC28` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC32` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC50` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC60` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC70` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC72` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC84` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC320` INT(5) NOT NULL DEFAULT '0';
ALTER TABLE  `hauled` ADD  `FulleriteC540` INT(5) NOT NULL DEFAULT '0';

ALTER TABLE  `runs` ADD  `CondensedAlloy` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CrystalCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PreciousAlloy` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SheenCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `GleamingAlloy` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `LucentCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `DarkCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `MotleyCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `LusteringAlloy` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `GlossyCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PlushCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `OpulentCompound` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CartesianTemporalCoordinator` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CentralSystemController` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `DefensiveControlNode` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ElectromechanicalHullSheeting` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `EmergentCombatAnalyzer` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `EmergentCombatIntelligence` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FusedNanomechanicalEngines` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `HeuristicSelfassemblers` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `JumpDriveControlNexus` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `MeltedNanoribbons` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ModifiedFluidRouter` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `NeurovisualInputMatrix` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PowderedC540Graphite` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ResonanceCalibrationMatrix` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ThermoelectricCatalysts` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC28` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC32` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC50` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC60` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC70` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC72` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC84` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC320` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC540` INT(6) NOT NULL DEFAULT '0';

ALTER TABLE  `runs` ADD  `CondensedAlloyWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CrystalCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PreciousAlloyWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `SheenCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `GleamingAlloyWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `LucentCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `DarkCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `MotleyCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `LusteringAlloyWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `GlossyCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PlushCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `OpulentCompoundWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CartesianTemporalCoordinatorWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `CentralSystemControllerWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `DefensiveControlNodeWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ElectromechanicalHullSheetingWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `EmergentCombatAnalyzerWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `EmergentCombatIntelligenceWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FusedNanomechanicalEnginesWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `HeuristicSelfassemblersWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `JumpDriveControlNexusWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `MeltedNanoribbonsWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ModifiedFluidRouterWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `NeurovisualInputMatrixWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `PowderedC540GraphiteWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ResonanceCalibrationMatrixWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `ThermoelectricCatalystsWanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC28Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC32Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC50Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC60Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC70Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC72Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC84Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC320Wanted` INT(6) NOT NULL DEFAULT '0';
ALTER TABLE  `runs` ADD  `FulleriteC540Wanted` INT(6) NOT NULL DEFAULT '0';

UPDATE config SET value='19' WHERE name='version';
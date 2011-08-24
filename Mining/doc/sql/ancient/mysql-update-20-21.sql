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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `shipvalues` (`id`, `modifier`, `time`, `AssaultShipValue`, `BattlecruiserValue`, `BattleshipValue`, `CarrierValue`, `CommandShipValue`, `CovertOpsValue`, `CruiserValue`, `DestroyerValue`, `DreadnoughtValue`, `ExhumerValue`, `FreighterValue`, `FrigateValue`, `HeavyAssaultShipValue`, `IndustrialShipValue`, `InterceptorValue`, `InterdictorValue`, `LogisticsShipValue`, `MiningBargeValue`, `ReconShipValue`, `ShuttleValue`, `TransportShipValue`) VALUES
(1, -1, 0, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000, 1.000);

ALTER TABLE `runs`  ADD `shipGlue` INT(4) NOT NULL DEFAULT '0' AFTER `oreGlue`;

INSERT INTO `config` (`name`, `value`) VALUES
('AssaultShipEnabled', '0'),
('BattlecruiserEnabled', '0'),
('BattleshipEnabled', '0'),
('CarrierEnabled', '0'),
('CommandShipEnabled', '0'),
('CovertOpsEnabled', '0'),
('CruiserEnabled', '0'),
('DestroyerEnabled', '0'),
('DreadnoughtEnabled', '0'),
('ExhumerEnabled', '0'),
('FreighterEnabled', '0'),
('FrigateEnabled', '0'),
('HeavyAssaultShipEnabled', '0'),
('IndustrialEnabled', '0'),
('InterceptorEnabled', '0'),
('InterdictorEnabled', '0'),
('LogisticsShipEnabled', '0'),
('MiningBargeEnabled', '0'),
('ReconShipEnabled', '0'),
('ShuttleEnabled', '0'),
('TransportShipEnabled', '0'),
('CapitalIndustrialShipEnabled', '0');

UPDATE config SET value='21' WHERE name='version';
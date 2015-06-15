-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Generation Time: Apr 27, 2012 at 02:25 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.10-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `whjew`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE IF NOT EXISTS `api_keys` (
  `userid` int(6) NOT NULL,
  `time` int(13) NOT NULL,
  `apiID` int(16) NOT NULL,
  `apiKey` varchar(200) NOT NULL,
  `api_valid` smallint(1) NOT NULL DEFAULT '0',
  `charid` int(20) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `apiID` (`apiID`),
  KEY `apiKey` (`apiKey`),
  KEY `charid` (`charid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `authkey` varchar(33) NOT NULL,
  `user` int(5) NOT NULL,
  `issued` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `disabled` int(1) NOT NULL DEFAULT '0',
  `agent` varchar(100) NOT NULL,
  PRIMARY KEY (`authkey`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cans`
--

CREATE TABLE IF NOT EXISTS `cans` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `pilot` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `droptime` int(15) NOT NULL,
  `isFull` int(1) NOT NULL DEFAULT '0',
  `miningrun` int(5) DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `pilot` (`pilot`),
  KEY `location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(100) NOT NULL,
  `value` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `config` (`name`,`value`) values
('sitename','WHBuddy'),
('version','28'),
('TTL', '120');
-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `sdesc` varchar(300) NOT NULL,
  `officer` varchar(30) NOT NULL,
  `system` varchar(30) NOT NULL,
  `security` varchar(3) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '1',
  `starttime` varchar(30) NOT NULL,
  `duration` varchar(30) NOT NULL,
  `difficulty` int(1) NOT NULL DEFAULT '1',
  `payment` varchar(100) NOT NULL DEFAULT '0',
  `collateral` bigint(255) NOT NULL DEFAULT '0',
  `notes` varchar(1000) DEFAULT NULL,
  `ships` varchar(2000) DEFAULT NULL,
  `signups` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE IF NOT EXISTS `failed_logins` (
  `incident` int(6) NOT NULL AUTO_INCREMENT,
  `time` int(13) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `username` varchar(30) NOT NULL,
  `username_valid` smallint(1) NOT NULL DEFAULT '0',
  `agent` varchar(100) NOT NULL,
  PRIMARY KEY (`incident`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hauled`
--

CREATE TABLE IF NOT EXISTS `hauled` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `miningrun` int(5) NOT NULL,
  `hauler` int(5) NOT NULL,
  `time` int(15) NOT NULL,
  `location` varchar(80) NOT NULL,
  `Item` varchar(256) NOT NULL,
  `Quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `miningrun` (`miningrun`),
  KEY `hauler` (`hauler`),
  KEY `location` (`location`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` bigint(20) NOT NULL,
  `text` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `textColor` varchar(9) NOT NULL,
  `bgColor` varchar(9) NOT NULL,
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `itemList`
--

CREATE TABLE IF NOT EXISTS `itemList` (
  `updateTime` varchar(10) DEFAULT NULL,
  `itemName` varchar(31) DEFAULT NULL,
  `friendlyName` varchar(50) DEFAULT NULL,
  `itemID` varchar(10) DEFAULT NULL,
  `value` decimal(16,2) DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `itemList` (`updateTime`, `itemName`, `itemID`, `value`) VALUES
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
('1315077942', 'Sleeper Drone AI Nexus', '30747', 0.00),
(0, 'Intact Armor Nanobot', 0, '0.00'), 
(0, 'Intact Electromechanical Component', 0, '0.00'), 
(0, 'Intact Power Cores', 0, '0.00'), 
(0, 'Intact Thruster Sections', 0, '0.00'), 
(0, 'Intact Weapon Subroutines', 0, '0.00'), 
(0, 'Malfunctioning Armor Nanobot', 0, '0.00'), 
(0, 'Malfunctioning Electromechanical Component', 0, '0.00'), 
(0, 'Malfunctioning Power Cores', 0, '0.00'), 
(0, 'Malfunctioning Thruster Sections', 0, '0.00'), 
(0, 'Malfunctioning Weapon Subroutines', 0, '0.00'), 
(0, 'Wrecked Armor Nanobot', 0, '0.00'), 
(0, 'Wrecked Electromechanical Component', 0, '0.00'), 
(0, 'Wrecked Power Cores', 0, '0.00'), 
(0, 'Wrecked Thruster Sections', 0, '0.00'), 
(0, 'Wrecked Weapon Subroutines', 0, '0.00');
-- --------------------------------------------------------

--
-- Table structure for table `joinups`
--

CREATE TABLE IF NOT EXISTS `joinups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(5) NOT NULL,
  `run` int(5) NOT NULL,
  `shiptype` int(2) NOT NULL,
  `joined` int(15) NOT NULL,
  `parted` int(15) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `remover` int(5) DEFAULT NULL,
  `charity` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `run` (`run`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lotteryTickets`
--

CREATE TABLE IF NOT EXISTS `lotteryTickets` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ticket` int(5) NOT NULL,
  `drawing` int(4) NOT NULL,
  `owner` int(5) NOT NULL DEFAULT '-1',
  `isWinner` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lotto`
--

CREATE TABLE IF NOT EXISTS `lotto` (
  `drawing` int(5) NOT NULL AUTO_INCREMENT,
  `opened` int(12) NOT NULL,
  `closed` int(12) NOT NULL,
  `isOpen` tinyint(1) NOT NULL DEFAULT '0',
  `winningTicket` int(5) DEFAULT NULL,
  `winner` int(5) DEFAULT NULL,
  `potSize` int(8) DEFAULT NULL,
  PRIMARY KEY (`drawing`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `onlinetime`
--

CREATE TABLE IF NOT EXISTS `onlinetime` (
  `userid` int(10) NOT NULL,
  `h00` int(1) NOT NULL DEFAULT '0',
  `h01` int(1) NOT NULL DEFAULT '0',
  `h02` int(1) NOT NULL DEFAULT '0',
  `h03` int(1) NOT NULL DEFAULT '0',
  `h04` int(1) NOT NULL DEFAULT '0',
  `h05` int(1) NOT NULL DEFAULT '0',
  `h06` int(1) NOT NULL DEFAULT '0',
  `h07` int(1) NOT NULL DEFAULT '0',
  `h08` int(1) NOT NULL DEFAULT '0',
  `h09` int(1) NOT NULL DEFAULT '0',
  `h10` int(1) NOT NULL DEFAULT '0',
  `h11` int(1) NOT NULL DEFAULT '0',
  `h12` int(1) NOT NULL DEFAULT '0',
  `h13` int(1) NOT NULL DEFAULT '0',
  `h14` int(1) NOT NULL DEFAULT '0',
  `h15` int(1) NOT NULL DEFAULT '0',
  `h16` int(1) NOT NULL DEFAULT '0',
  `h17` int(1) NOT NULL DEFAULT '0',
  `h18` int(1) NOT NULL DEFAULT '0',
  `h19` int(1) NOT NULL DEFAULT '0',
  `h20` int(1) NOT NULL DEFAULT '0',
  `h21` int(1) NOT NULL DEFAULT '0',
  `h22` int(1) NOT NULL DEFAULT '0',
  `h23` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `opTypes`
--

CREATE TABLE IF NOT EXISTS `opTypes` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `opName` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opName` (`opName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orderItems`
--

CREATE TABLE IF NOT EXISTS `orderItems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `total` double NOT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orevalues`
--

CREATE TABLE IF NOT EXISTS `orevalues` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `modifier` int(5) NOT NULL,
  `time` int(15) NOT NULL,
  `Item` varchar(256) NOT NULL,
  `Worth` decimal(16,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payoutRequests`
--

CREATE TABLE IF NOT EXISTS `payoutRequests` (
  `request` int(6) NOT NULL AUTO_INCREMENT,
  `time` int(12) NOT NULL,
  `applicant` int(5) NOT NULL,
  `amount` bigint(255) NOT NULL,
  `payoutTime` int(12) DEFAULT NULL,
  `banker` int(5) DEFAULT NULL,
  PRIMARY KEY (`request`),
  KEY `applicant` (`applicant`),
  KEY `banker` (`banker`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `userid` int(5) NOT NULL,
  `emailVisible` tinyint(1) NOT NULL DEFAULT '0',
  `isMiner` tinyint(1) NOT NULL DEFAULT '0',
  `isHauler` tinyint(1) NOT NULL DEFAULT '0',
  `isFighter` tinyint(1) NOT NULL DEFAULT '0',
  `about` blob,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

CREATE TABLE IF NOT EXISTS `ranks` (
  `rankid` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `rankOrder` varchar(3) DEFAULT NULL,
  UNIQUE KEY `rankid` (`rankid`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `ranks` (`name`) values
('Admin'),
('Member');

-- --------------------------------------------------------

--
-- Table structure for table `runs`
--

CREATE TABLE IF NOT EXISTS `runs` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `location` varchar(50) NOT NULL,
  `starttime` int(15) NOT NULL,
  `endtime` int(15) DEFAULT NULL,
  `supervisor` int(5) NOT NULL,
  `corpkeeps` int(3) NOT NULL DEFAULT '100',
  `isOfficial` tinyint(1) NOT NULL DEFAULT '0',
  `isLocked` tinyint(1) NOT NULL DEFAULT '0',
  `oreGlue` int(15) NOT NULL DEFAULT '0',
  `shipGlue` int(4) NOT NULL DEFAULT '0',
  `tmec` float NOT NULL DEFAULT '0',
  `optype` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`location`,`supervisor`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shipvalues`
--

CREATE TABLE IF NOT EXISTS `shipvalues` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `modifier` int(5) NOT NULL,
  `time` int(15) NOT NULL,
  `AssaultShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `BattlecruiserValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `BattleshipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `CarrierValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `CommandShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `CovertOpsValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `CruiserValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `DestroyerValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `DreadnoughtValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `ExhumerValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `FreighterValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `FrigateValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `HeavyAssaultShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `IndustrialShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `InterceptorValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `InterdictorValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `LogisticsShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `MiningBargeValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `ReconShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `ShuttleValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `TransportShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  `CapitalIndustrialShipValue` decimal(5,4) NOT NULL DEFAULT '1.0000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `shipvalues` (`id`, `modifier`, `time`, `AssaultShipValue`, `BattlecruiserValue`, `BattleshipValue`, `CarrierValue`, `CommandShipValue`, `CovertOpsValue`, `CruiserValue`, `DestroyerValue`, `DreadnoughtValue`, `ExhumerValue`, `FreighterValue`, `FrigateValue`, `HeavyAssaultShipValue`, `IndustrialShipValue`, `InterceptorValue`, `InterdictorValue`, `LogisticsShipValue`, `MiningBargeValue`, `ReconShipValue`, `ShuttleValue`, `TransportShipValue`, `CapitalIndustrialShipValue`) VALUES 
(NULL, '0', '0', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000', '1.0000');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `descr` varchar(80) DEFAULT NULL,
  `template` blob,
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `templates` (`id`, `identifier`, `type`, `descr`, `template`) VALUES
(1, 'activation', 'email', 'Account activation email', 0x48656c6c6f207b7b555345524e414d457d7d20210a0a596f75722043454f20686173206a75737420636f6e6669726d656420796f7572206163636f756e742c20616e6420776520617265206d6f7265207468616e0a686170707920746f2070726573656e7420796f7520796f75722070617373776f72642e0a0a506c6561736520757365207468652070617373776f72643a207b7b4e4557504153537d7d0a0a506c65617365206c6f6720696e20746f20796f75206561726c6965737420636f6e76656e69656e636520616e64206368616e676520746861740a70617373776f726420746f20736f6d657468696e672065617369657220746f2072656e656d6265722e204f722077726974652074686973206f6e650a646f776e2e0a0a7b7b56455253494f4e7d7d206f66207b7b534954454e414d457d7d2e0a0a53656520796f7520696e207468652061737465726f6964206669656c647320736f6f6e2c0a0a7b7b534954454e414d457d7d2e),
(2, 'lostpass', 'email', 'Lost password email', 0x48656c6c6f207b7b555345524e414d457d7d20210a0a536f6d656f6e652066726f6d207b7b49507d7d202d706f737369626c7920796f752d2068617320726563656e746c792076697369746564207468650a7b7b56455253494f4e7d7d206f66207b7b534954454e414d457d7d2e0a0a486f77657665722c20796f75206f722068652f73686520776173206e6f742061626c6520746f206c6f6720696e20616e64206861730a7265717565737465642061206e65772070617373776f72642c2077686963682077652061726520686170707920746f20737570706c7920746f20796f753a0a0a7b7b4e4557504153537d7d0a0a506c65617365206c6f6720696e20746f20796f75206561726c6965737420636f6e76656e69656e636520616e64206368616e676520746861740a70617373776f726420746f20736f6d657468696e672065617369657220746f2072656e656d6265722e204f722077726974652074686973206f6e650a646f776e2e0a0a53656520796f7520696e207468652061737465726f6964206669656c647320736f6f6e2c0a0a7b7b534954454e414d457d7d2e),
(3, 'newevent', 'email', 'New event accounced email', 0x4772656574696e6773207b7b555345527d7d210a7b7b464c41474f4646494345527d7d20686173206a75737420616e6e6f756e6365642061206e6577204576656e74210a0a52656e656d6265723a20496620796f752077616e7420746f20636f6d652c20706c65617365206c6f67696e207769746820796f75720a202020202020202020206163636f756e7420616e64206a6f696e2074686973206576656e742e20546869732068656c7073207468650a202020202020202020206f6666696365727320696e2063686172676520746f206b6e6f7720686f77206d616e79206172650a20202020202020202020636f6d696e672e0a0a2d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d200a0a4556454e5420494e464f524d4154494f4e0a0a4d697373696f6e204944202020202020202020207b7b49447d7d0a4d697373696f6e205479706520202020202020207b7b545950457d7d0a4465736372697074696f6e2020202020202020207b7b5344455343527d7d0a457865637574696e67204f6666696365722020207b7b464c41474f4646494345527d7d0a53797374656d20202020202020202020202020207b7b53595354454d7d7d0a53656375726974792020202020202020202020207b7b53454355524954597d7d0a537461727474696d6520202020202020202020207b7b535441525454494d457d7d0a457374696d61746564204475726174696f6e20207b7b4455524154494f4e7d7d0a446966666963756c7479202020202020202020207b7b5249534b7d7d0a5061796d656e74202020202020202020202020207b7b5041594d454e547d7d0a436f6c6c61746572616c202020202020202020207b7b434f4c4c41544552414c7d7d0a0a4164646974696f6e616c204e6f7465733a0a7b7b4e4f5445537d7d0a0a2d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d200a0a596f752072656369766564207468697320654d61696c206265636175736520796f7520617265207375627363726962656420746f0a74686520654d61696c20616e6e6f756e63656d656e742073797374656d206f66204d696e696e6742756464792c200a62656c6f6e67696e6720746f207b7b534954454e414d457d7d2e0a496620796f7520646f206e6f74207769736820746f2072656369766520616e79206d6f726520654d61696c732066726f6d0a7468697320736974652c20706c6561736520676f20746f2074686520666f6c6c6f77696e6720736974652c206c6f67696e0a616e64206f70742d6f75742028707265666572656e6365732070616765292e0a0a7b7b55524c7d7d0a0a2d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d202d2d20383c202d2d200a706f7765726564206279207b7b56455253494f4e7d7d2e),
(4, 'accountactivated', 'email', 'Account activated email', 0x48656c6c6f207b7b555345524e414d457d7d20210a0a596f757220667269656e646c7920636f72706f726174696f6e2c207b7b434f52507d7d2c0a686173206a757374206372656174656420616e206163636f756e7420666f7220796f7520666f7220746865204d696e696e6742756464790a6174207b7b534954457d7d2e0a0a596f7572206c6f67696e206e616d653a207b7b555345524e414d457d7d0a596f75722050617373776f726420203a207b7b50415353574f52447d7d0a0a506c65617365206c6f6720696e20746f20796f75206561726c6965737420636f6e76656e69656e636520616e64206368616e676520746861740a70617373776f726420746f20736f6d657468696e672065617369657220746f2072656e656d6265722e204f722077726974652074686973206f6e650a646f776e2e0a0a53656520796f7520696e207468652061737465726f6964206669656c647320736f6f6e2c0a0a7b7b43524541544f527d7d0a7b7b434f52507d7d2e),
(5, 'accountrequest', 'email', 'Account activation email', 0x48656c6c6f2074686572652c2070696c6f74210a0a536f6d656f6e652066726f6d20746865204950207b7b49507d7d2c20706f737369626c7920796f752c2072657175657374656420616e0a6163636f756e7420666f7220746865204d696e696e674275646479206465706c6f796d656e74206c6f63617465642061740a0a7b7b55524c7d7d0a0a546865207265717565737420776173206d616465206f6e207b7b444154457d7d2e0a0a496620796f75207265717565737465642069742c20796f75206e65656420746f20616374697661746520796f7572206163636f756e740a627920636c69636b696e67206f6e2074686973206c696e6b3a0a0a7b7b41435449564154457d7d0a0a496620746865207265717565737420776173206d61646520696e206572726f722c206f72206e6f7420627920796f752c20646f0a6e6f7420776f7272792e20496620796f7520646f206e6f7420646f20616e797468696e6720796f757220656d61696c206164726573730a77696c6c20626520626c61636b6c697374656420696e206f75722073797374656d20616e64206e6f74206265207573656420616761696e2e0a0a5468616e6b20796f752c0a6b696e6420726567617264732c0a5468652043454f206f66207b7b434f52507d7d2e),
(6, 'receipt', 'email', 'Summary of mining op', 0x7b7b44495649444552544f507d7d0a0a48656c6c6f207b7b555345524e414d457d7d2c0a0a5468616e6b20796f7520666f7220617474656e64696e67206d696e696e67206f7065726174696f6e20237b7b49447d7d2e2042656c6f772069732061206c696e6575700a6f6620796f7572206f7065726174696f6e7320616368696576656d656e743a0a0a4f726573204d696e65643a0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d0a7b7b4f5245534d494e45447d7d0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d0a20202020202020202020202020202020202020202020202020202020202020202020202020202020544f54414c2056414c55453a207b7b56414c55457d7d0a2020202020202020202020202020202020202020202020202020202020202020202020594f55522047524f53532056414c55453a207b7b47524f535353484152457d7d0a2020202020202020202020202020202020202020202020202020202020202020202020202020202020434f52502054415845533a207b7b434f525054415845537d7d0a2020202020202020202020202020202020202020202020202020202020202020202020202020202020204e45542056414c55453a207b7b4e455456414c55457d7d0a20202020202020202020202020202020202020202020202020202020202020202020202020594f5552204e45542053484152453a207b7b4e455453484152457d7d0a0a0a54686520616d6f6e756e74206f66207b7b4e455453484152457d7d20686173206265656e20637265646974656420746f20796f7572206163636f756e742c0a62656c6f7720697320612073686f72742073756d6d617279206f6620796f757220726563656e74207472616e73616374696f6e733a0a0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d0a7b7b4143434f554e547d7d0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d0a7b7b4143434f554e5442414c414e43457d7d0a0a5468616e6b20796f7520616761696e20666f722074616b696e67207061727420696e2074686973206d696e696e67206f7065726174696f6e2e20576520686f706520746f0a73656520796f7520696e206675747572652072756e732061732077656c6c210a0a7b7b534954454e414d457d7d0a7b7b55524c7d7d0a0a7b7b44495649444552424f547d7d),
(7, 'motd', 'announce', 'Announcement after login', '');


-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time` int(12) NOT NULL,
  `owner` int(5) NOT NULL,
  `banker` int(5) NOT NULL,
  `type` int(2) NOT NULL DEFAULT '0',
  `amount` bigint(32) NOT NULL DEFAULT '0',
  `reason` varchar(200) NOT NULL DEFAULT 'cash deposit',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`,`banker`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(80) DEFAULT 'System',
  `addedby` int(5) NOT NULL DEFAULT '0',
  `lastlogin` int(20) DEFAULT '0',
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `emailvalid` int(1) NOT NULL DEFAULT '0',
  `emailcode` bigint(30) DEFAULT NULL,
  `authID` int(11) DEFAULT NULL,
  `authPrimary` tinyint(1) NOT NULL DEFAULT '0',
  `optIn` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `canLogin` tinyint(1) NOT NULL DEFAULT '1',
  `canJoinRun` tinyint(1) NOT NULL DEFAULT '1',
  `canCreateRun` tinyint(1) NOT NULL DEFAULT '1',
  `canCloseRun` tinyint(1) NOT NULL DEFAULT '1',
  `canDeleteRun` tinyint(1) NOT NULL DEFAULT '0',
  `canAddHaul` tinyint(1) NOT NULL DEFAULT '1',
  `canChangePwd` tinyint(1) NOT NULL DEFAULT '1',
  `canChangeEmail` tinyint(1) NOT NULL DEFAULT '1',
  `canChangeOre` tinyint(1) NOT NULL DEFAULT '0',
  `canAddUser` tinyint(1) NOT NULL DEFAULT '0',
  `canSeeUsers` tinyint(1) NOT NULL DEFAULT '0',
  `canDeleteUser` tinyint(1) NOT NULL DEFAULT '0',
  `canEditRank` tinyint(1) NOT NULL DEFAULT '0',
  `canManageUser` tinyint(1) NOT NULL DEFAULT '0',
  `canEditEvents` tinyint(1) NOT NULL DEFAULT '0',
  `canDeleteEvents` tinyint(1) NOT NULL DEFAULT '0',
  `canSeeEvents` tinyint(1) NOT NULL DEFAULT '1',
  `isOfficial` tinyint(1) NOT NULL DEFAULT '1',
  `isLottoOfficial` tinyint(1) NOT NULL DEFAULT '0',
  `canPlayLotto` tinyint(1) NOT NULL DEFAULT '1',
  `isAccountant` tinyint(1) NOT NULL DEFAULT '0',
  `lottoCredit` int(5) NOT NULL DEFAULT '0',
  `lottoCreditsSpent` int(5) NOT NULL DEFAULT '0',
  `preferences` blob,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `rank` int(3) DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `username` (`username`,`password`,`email`,`confirmed`,`emailvalid`,`optIn`,`deleted`,`canLogin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

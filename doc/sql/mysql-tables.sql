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
('version','25'),
('TTL', '120')
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1506 ;

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
  `itemID` varchar(10) DEFAULT NULL,
  `value` decimal(16,2) DEFAULT '0.00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=544 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `m3values`
--

CREATE TABLE IF NOT EXISTS `m3values` (
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=464 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=190 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=588 ;

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
  `canChangePwd` tinyint(1) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

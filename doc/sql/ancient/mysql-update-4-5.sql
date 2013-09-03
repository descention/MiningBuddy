/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/ancient/mysql-update-4-5.txt,v 1.1 2007/06/10 13:45:49 mining Exp $
 *
 * Copyright (c) 2005, 2006, 2007 Christian Reiss.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms,
 * with or without modification, are permitted provided
 * that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 *  OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 *  TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 *  OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 *  OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
 

ALTER TABLE `hauled` ADD `CrimsonArkonor` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `PrimeArkonor` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `MonoclinicBistot` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `TriclinicBistot` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `CrystallineCrokite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `SharpCrokite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `ObsidianOchre` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `OnyxOchre` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `IridescentGneiss` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `PrismaticGneiss` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `GlazedHedbergite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `VitricHedbergite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `VividHemorphite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `RadiantHemorphite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `PureJaspet` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `PristineJaspet` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `FieryKernite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `LuminousKernite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `MagmaMercoxit` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `VitreousMercoxit` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `GleamingSpodumain` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `BrightSpodumain` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `SilveryOmber` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `GoldenOmber` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `AzurePlagioclase` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `RichPlagioclase` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `SolidPyroxeres` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `ViscousPyroxeres` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `CondensedScordite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `MassiveScordite` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `ConcentratedVeldspar` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `DenseVeldspar` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `EnrichedClearIcicle` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `SmoothGlacialMass` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `ThickBlueIce` int(5) NOT NULL default '0';
ALTER TABLE `hauled` ADD `WhiteGlaze` int(5) NOT NULL default '0';


ALTER TABLE `orevalues` ADD `CrimsonArkonorWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `PrimeArkonorWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `MonoclinicBistotWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `TriclinicBistotWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `CrystallineCrokiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `SharpCrokiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `ObsidianOchreWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `OnyxOchreWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `IridescentGneissWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `PrismaticGneissWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `GlazedHedbergiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `VitricHedbergiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `VividHemorphiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `RadiantHemorphiteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `PureJaspetWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `PristineJaspetWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `FieryKerniteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `LuminousKerniteWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `SilveryOmberWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `GoldenOmberWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `MagmaMercoxitWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `VitreousMercoxitWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `GleamingSpodumainWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `BrightSpodumainWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `AzurePlagioclaseWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `RichPlagioclaseWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `SolidPyroxeresWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `ViscousPyroxeresWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `CondensedScorditeWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `MassiveScorditeWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `ConcentratedVeldsparWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `DenseVeldsparWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `EnrichedClearIcicleWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `SmoothGlacialMassWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `ThickBlueIceWorth` int(10) NOT NULL default '0';
ALTER TABLE `orevalues` ADD `WhiteGlazeWorth` int(10) NOT NULL default '0';


ALTER TABLE `runs` ADD `CrimsonArkonor` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PrimeArkonor` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MonoclinicBistot` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `TriclinicBistot` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `CrystallineCrokite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SharpCrokite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ObsidianOchre` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `OnyxOchre` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SilveryOmber` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GoldenOmber` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `IridescentGneiss` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PrismaticGneiss` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GlazedHedbergite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VitricHedbergite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VividHemorphite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `RadiantHemorphite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PureJaspet` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PristineJaspet` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `FieryKernite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `LuminousKernite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MagmaMercoxit` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VitreousMercoxit` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GleamingSpodumain` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `BrightSpodumain` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `AzurePlagioclase` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `RichPlagioclase` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SolidPyroxeres` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ViscousPyroxeres` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `CondensedScordite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MassiveScordite` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ConcentratedVeldspar` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `DenseVeldspar` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `EnrichedClearIcicle` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SmoothGlacialMass` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ThickBlueIce` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `WhiteGlaze` int(6) NOT NULL default '0';

ALTER TABLE `runs` ADD `CrimsonArkonorWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PrimeArkonorWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MonoclinicBistotWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `TriclinicBistotWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `CrystallineCrokiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SharpCrokiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ObsidianOchreWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `OnyxOchreWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `IridescentGneissWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PrismaticGneissWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GlazedHedbergiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VitricHedbergiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VividHemorphiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `RadiantHemorphiteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PureJaspetWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `PristineJaspetWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SilveryOmberWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GoldenOmberWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `FieryKerniteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `LuminousKerniteWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MagmaMercoxitWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `VitreousMercoxitWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `GleamingSpodumainWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `BrightSpodumainWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `AzurePlagioclaseWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `RichPlagioclaseWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SolidPyroxeresWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ViscousPyroxeresWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `CondensedScorditeWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `MassiveScorditeWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ConcentratedVeldsparWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `DenseVeldsparWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `EnrichedClearIcicleWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `SmoothGlacialMassWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `ThickBlueIceWanted` int(6) NOT NULL default '0';
ALTER TABLE `runs` ADD `WhiteGlazeWanted` int(6) NOT NULL default '0';

ALTER TABLE `joinups` ADD `charity` tinyint(1) NOT NULL default '0';

insert into config (name, value) values ("CrimsonArkonorEnabled", "0");
insert into config (name, value) values ("PrimeArkonorEnabled", "0");
insert into config (name, value) values ("MonoclinicBistotEnabled", "0");
insert into config (name, value) values ("TriclinicBistotEnabled", "0");
insert into config (name, value) values ("CrystallineCrokiteEnabled", "0");
insert into config (name, value) values ("SharpCrokiteEnabled", "0");
insert into config (name, value) values ("ObsidianOchreEnabled", "0");
insert into config (name, value) values ("OnyxOchreEnabled", "0");
insert into config (name, value) values ("IridescentGneissEnabled", "0");
insert into config (name, value) values ("PrismaticGneissEnabled", "0");
insert into config (name, value) values ("GlazedHedbergiteEnabled", "0");
insert into config (name, value) values ("VitricHedbergiteEnabled", "0");
insert into config (name, value) values ("VividHemorphiteEnabled", "0");
insert into config (name, value) values ("RadiantHemorphiteEnabled", "0");
insert into config (name, value) values ("PureJaspetEnabled", "0");
insert into config (name, value) values ("PristineJaspetEnabled", "0");
insert into config (name, value) values ("FieryKerniteEnabled", "0");
insert into config (name, value) values ("LuminousKerniteEnabled", "0");
insert into config (name, value) values ("MagmaMercoxitEnabled", "0");
insert into config (name, value) values ("VitreousMercoxitEnabled", "0");
insert into config (name, value) values ("GleamingSpodumainEnabled", "0");
insert into config (name, value) values ("BrightSpodumainEnabled", "0");
insert into config (name, value) values ("AzurePlagioclaseEnabled", "0");
insert into config (name, value) values ("RichPlagioclaseEnabled", "0");
insert into config (name, value) values ("SilveryOmberEnabled", "0");
insert into config (name, value) values ("GoldenOmberEnabled", "0");
insert into config (name, value) values ("SolidPyroxeresEnabled", "0");
insert into config (name, value) values ("ViscousPyroxeresEnabled", "0");
insert into config (name, value) values ("CondensedScorditeEnabled", "0");
insert into config (name, value) values ("MassiveScorditeEnabled", "0");
insert into config (name, value) values ("ConcentratedVeldsparEnabled", "0");
insert into config (name, value) values ("DenseVeldsparEnabled", "0");
insert into config (name, value) values ("EnrichedClearIcicleEnabled", "0");
insert into config (name, value) values ("SmoothGlacialMassEnabled", "0");
insert into config (name, value) values ("ThickBlueIceEnabled", "0");
insert into config (name, value) values ("WhiteGlazeEnabled", "0");

ALTER TABLE `users` ADD `canDeleteEvents` tinyint(1) NOT NULL default '0';

UPDATE config SET value='5' WHERE name='version';

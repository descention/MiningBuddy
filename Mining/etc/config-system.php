<?PHP


/* 
 * MiningBuddy (http://miningbuddy.net)  
 * $Header: /usr/home/mining/cvs/mining/etc/config-system.php,v 1.116 2008/05/01 15:37:24 mining Exp $
 *
 * Copyright (c) 2005-2008 Christian Reiss.
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

/*
 * DO NOT EDIT ANYTHING -A N Y T H I N G- in this file.
 * Doing so will not only kill a kitten, drown puppies,
 * increase lag in eve, abduct santa and tickle Parrowdox,
 * no, it will also most assuredly break your MiningBuddy 
 * installation.
 */

$VERSION_COMP = "0.8.6.38";
$VERSION = "MiningBuddy Plus " . $VERSION_COMP;

$DSN = "$mysql_protocol://$mysql_username:$mysql_password@$mysql_hostname/$mysql_dbname";

// Market Arrays

$OTYPENAME = array ( "buy", "sell");
$PRICECRITERIA = array ( "min", "max", "median" );

// Ore Arrays

$ORENAMES = array (
		// Standard ore
	"Arkonor", "Crimson Arkonor", "Prime Arkonor",
	"Bistot", "Triclinic Bistot", "Monoclinic Bistot",
	"Crokite", "Sharp Crokite", "Crystalline Crokite",
	"Dark Ochre", "Onyx Ochre",	"Obsidian Ochre",
	"Gneiss", "Iridescent Gneiss", "Prismatic Gneiss",
	"Hedbergite", "Glazed Hedbergite", "Vitric Hedbergite",
	"Hemorphite", "Vivid Hemorphite", "Radiant Hemorphite",
	"Jaspet", "Pure Jaspet", "Pristine Jaspet",
	"Kernite", "Luminous Kernite", "Fiery Kernite",
	"Mercoxit", "Magma Mercoxit", "Vitreous Mercoxit",
	"Omber", "Silvery Omber", "Golden Omber",
	"Spodumain", "Bright Spodumain", "Gleaming Spodumain",
	"Plagioclase", "Azure Plagioclase", "Rich Plagioclase",
	"Pyroxeres", "Solid Pyroxeres", "Viscous Pyroxeres",
	"Scordite", "Condensed Scordite", "Massive Scordite",
	"Veldspar", "Concentrated Veldspar", "Dense Veldspar",

		// Ice
	"Blue Ice",
	"Clear Icicle",
	"Dark Glitter",
	"Enriched Clear Icicle",
	"Gelidus",
	"Glacial Mass",
	"Glare Crust",
	"Krystallos",
	"Pristine White Glaze",
	"Smooth Glacial Mass",
	"Thick Blue Ice",
	"White Glaze",
	
		// Compounds
	"Condensed Alloy",
	"Crystal Compound",
	"Precious Alloy",
	"Sheen Compound",
	"Gleaming Alloy",
	"Lucent Compound",
	"Dark Compound",
	"Motley Compound",
	"Lustering Alloy",
	"Glossy Compound",
	"Plush Compound",
	"Opulent Compound",
	
		// Salvage
	"Cartesian Temporal Coordinator",
	"Central System Controller",
	"Defensive Control Node",
	"Electromechanical Hull Sheeting",
	"Emergent Combat Analyzer",
	"Emergent Combat Intelligence",
	"Fused Nanomechanical Engines",
	"Heuristic Selfassemblers",
	"Jump Drive Control Nexus",
	"Melted Nanoribbons",
	"Modified Fluid Router",
	"Neurovisual Input Matrix",
	"Powdered C-540 Graphite",
	"Resonance Calibration Matrix",
	"Thermoelectric Catalysts",
	
		// Wormhole Gas
	"Fullerite-C28",
	"Fullerite-C32",	
	"Fullerite-C50",
	"Fullerite-C60",
	"Fullerite-C70",
	"Fullerite-C72",
	"Fullerite-C84",
	"Fullerite-C320",
	"Fullerite-C540",
	
		// Sleeper Loot (Blue-Tag)
	"Neural Network Analyzer",
	"Sleeper Data Library",
	"Ancient Coordinates Database",
	"Sleeper Drone AI Nexus"
	
);

foreach ($ORENAMES as $ore) {
	$dbfriendly = str_replace(" ", "", ucwords($ore));
	$dbfriendly = str_replace("-", "", ucwords($dbfriendly));
	if (!empty ($ORENAME_STR)) {
		$ORENAME_STR .= ", " . $dbfriendly;
	} else {
		$ORENAME_STR = $dbfriendly;
	}
	$DBORE[$ore] = $dbfriendly;
}

// Ship Array

$SHIPTYPES = array (
	"Assault Ship",
	"Battlecruiser",
	"Battleship",
	"Carrier",
	"Command Ship",
	"Covert Ops",
	"Cruiser",
	"Destroyer",
	"Dreadnought",
	"Exhumer",
	"Freighter",
	"Frigate",
	"Heavy Assault Ship",
	"Industrial Ship",
	"Interceptor",
	"Interdictor",
	"Logistics Ship",
	"Mining Barge",
	"Recon Ship",
	"Shuttle",
	"Transport Ship",
	"Capital Industrial Ship"
);

$SHIPTYPES[99] = "unclassified";

foreach ($SHIPTYPES as $ship) {
	$dbfriendly = str_replace(" ", "", ucwords($ship));
	if (!empty ($SHIPTYPE_STR)) {
		$SHIPTYPE_STR .= ", " . $dbfriendly;
	} else {
		$SHIPTYPE_STR = $dbfriendly;
	}
	$DBSHIP[$ship] = $dbfriendly;
}

// Refined Materials Array
$MATERIALS = array (
		//Minerals
	"Tritanium", 
	"Pyerite", 
	"Mexallon", 
	"Isogen", 
	"Nocxium", 
	"Zydrine", 
	"Megacyte", 
	"Morphite",
		
		//Ice Product 
	"Nitrogen Isotopes", 
	"Hydrogen Isotopes", 
	"Oxygen Isotopes", 
	"Helium Isotopes", 
	"Liquid Ozone", 
	"Heavy Water", 
	"Strontium"
);

foreach ($MATERIALS as $mat) {
	$dbfriendly = str_replace(" ", "", ucwords($mat));
	if (!empty ($MATERIAL_STR)) {
		$MATERIAL_STR .= ", " . $dbfriendly;
	} else {
		$MATERIAL_STR = $dbfriendly;
	}
	$DBMAT[$mat] = $dbfriendly;
}

// Config Data

$SQLVER = "23";
$CONFIGVER = "10";
$IS_BETA = false;
?>
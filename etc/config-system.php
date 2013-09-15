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

$VERSION_COMP = "1.1";
$VERSION = "Wormhole Buddy (WHBuddy) " . $VERSION_COMP;

$DSN = "$mysql_protocol://$mysql_username:$mysql_password@$mysql_hostname/$mysql_dbname";

// Market Arrays

$OTYPENAME = array ( "buy", "sell");
$PRICECRITERIA = array ( "min", "max", "median" );


include ('./etc/item-gen.php');

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
	"Capital Industrial Ship",
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

// Config Data

$SQLVER = "28";
$CONFIGVER = "10";
$IS_BETA = false;
?>
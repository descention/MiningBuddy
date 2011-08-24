<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/addRun.php,v 1.28 2008/01/02 20:01:32 mining Exp $
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

function addRun() {
	// We need some more globals.
	global $DB;
	global $ORENAMES;
	global $DBORE;
	global $ORENAMES_STR;
	global $MySelf;
	global $TIMEMARK;

	// Set the userID
	$userID = $MySelf->getID();

	// Are we permitted to create a new run?    
	if (!$MySelf->canCreateRun()) {
		makeNotice("You are not allowed to create a mining op!", "error", "forbidden");
	}

	if ($_POST[startnow]) {
		$starttime = $TIMEMARK;
	} else {
		// Startting time goodness.
		$myTime = array (
			"day" => "$_POST[ST_day]",
			"month" => "$_POST[ST_month]",
			"year" => "$_POST[ST_year]",
			"hour" => "$_POST[ST_hour]",
			"minute" => "$_POST[ST_minute]",

			
		);
		$starttime = humanTime("toUnix", $myTime);
	}

	// Having fun with checkboxes, yet again.
	if ($_POST[isOfficial] == "on") {
		$official = true;
	} else {
		$official = false;
	}

	// We using either predefined locations.
	if (empty ($_POST[location])) {
		$location = $_POST[locations];
	} else {
		$location = $_POST[location];
	}
	
	if (empty ($location)) {
		makeNotice("You need to specify the location of the Mining Operation!", "notice", "Where again?", "index.php?action=newrun", "[Cancel]");
	}

	// Supervisor
	if ($MySelf->isOfficial()) {
		if (empty ($_POST[supervisor])) {
			// Is official, but no one named!
			makeNotice("You need to name someone as the supervisor for this run!", "warning", "Missing Information", "index.php?action=newrun", "[Cancel]");
		} else {
			// Grab ID of named supervisor.
			$supervisor = usernameToID(sanitize($_POST[supervisor]));
		}
	} else {
		// Non official, use own ID
		$supervisor = $MySelf->getID();
	}
	
	// Corp tax
	if ($MySelf->isOfficial()) {
		if ($_POST[corpkeeps] > 100 || $_POST[corpkeeps] < 0 || !numericCheckBool($_POST[corpkeeps])) {
			makeNotice("The corporation can not keep more than 100% and most certainly wont pay out more than the gross worth (values below 0%). A value of " . $_POST[corpkeeps] . " is really not valid.", "warning", "Out of range", "index.php?action=newrun", "[Cancel]");
		} else {
			$tax = $_POST[corpkeeps];
		}
	} else {
		$tax = "0";
	}
	
	// Get the current ore-values.
	$oreValue = $DB->getCol("SELECT max(id) FROM orevalues");
	$oreValue = $oreValue[0];
	
//Edit Starts Here	
	$shipValue = $DB->getCol("SELECT max(id) FROM shipvalues");
	$shipValue = $shipValue[0];
//Edit Ends Here

	$DB->query("insert into runs (location, starttime, supervisor, corpkeeps, isOfficial, oreGlue, shipGlue) " . "values (?,?,?,?,?,?,?)", array (
		"$location",
		"$starttime",
		"$supervisor",
		$tax,
		$official,
		$oreValue,
//Edit Starts Here		
		$shipValue,
//Edit Ends Here		
	));

	// Check for success.
	if ($DB->affectedRows() != 1){
		makeNotice("DB Error: Could not add run to database!", "error", "DB Error");
	}

	// Now update the "required" ore values.
	foreach ($DBORE as $ORE) {
		// But the ore needs to be set, valid (numeric) and must be activated.
		if ((isset ($_POST[$ORE])) && (is_numeric($_POST[$ORE])) && (getOreSettings($ORE) == true) && ($_POST[$ORE] > 0)) {
			$DB->query("UPDATE runs SET " . $ORE . "Wanted='" . $_POST[$ORE] . "' WHERE $starttime='$starttime'");
		}
	}

	// And return the user to the run-list overview page.
	makeNotice("The new Mining Operation has been created in the database.", "notice", "Mining Operation created", "index.php?action=list", "[OK]");
}
?>
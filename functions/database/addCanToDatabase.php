<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/addCanToDatabase.php,v 1.14 2008/01/02 20:01:32 mining Exp $
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

function addCanToDatabase() {

	// We need some more globals.
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	global $PREFS;

	// Change of prefs?
	switch ($_POST[canprefs]) {
		case ("0") :
			$PREFS->setPref("CanNaming", "0");
			break;

		case ("1") :
			$PREFS->setPref("CanNaming", "1");
			break;

		case ("2") :
			$PREFS->setPref("CanNaming", "2");
			break;
	}

	if ($_POST[forRun]) {
		$PREFS->setPref("CanForRun", "1");
	} else {
		$PREFS->setPref("CanForRun", "0");
	}

	// Save the modified Preferences.
	$PREFS->storePrefs();

	// Wash the incoming stuff.
	$cantag = sanitize("$_POST[cantag]");
	$location = sanitize("$_POST[location]");
	$location2 = sanitize("$_POST[location2]");
	$droptime = date("U", strtotime(sanitize("$_POST[droptime]")));
	$id = $MySelf->getID();

	// If user entered special location, use that.
	if ($location2 != "") {
		$location = $location2;
	}

	// Startting time goodness.
	$myTime = array (
		"day" => "$_POST[ST_day]",
		"month" => "$_POST[ST_month]",
		"year" => "$_POST[ST_year]",
		"hour" => "$_POST[ST_hour]",
		"minute" => "$_POST[ST_minute]",
		
	);
	$droptime = humanTime("toUnix", $myTime);

	// is the time valid?
	if (!$droptime) {
		makeNotice("Invalid time supplied!", "error", "Invalid Time!");
	}

	// We got a name?
	if (empty ($cantag)) {
		makeNotice("You need to supply a name for your can!", "error", "Can not added", "index.php?action=cans", "[cancel]");
	}

	// We got a location?
	if (empty ($location)) {
		makeNotice("You need to supply the location of your can!", "error", "Can not added", "index.php?action=cans", "[cancel]");
	}

	// Can for mining operation?
	$myRun = userInRun($MySelf->getID());

	if ($_POST[forRun] && $myRun) {
		$forrun = $myRun;
	} else {
		$forrun = -1;
	}

	// Insert the can into the database.
	$P = $DB->query("INSERT INTO cans (pilot, location, droptime, name, miningrun) VALUES (?,?,?,?,?)", array (
		"$id",
		"$location",
		"$droptime",
		"$cantag",
		$forrun
	));

	// Did we encounter an error?
	if ($DB->affectedRows() == 1) {

		$to_old = $TIMEMARK -7200;
		$DB->query("DELETE FROM cans WHERE droptime < $to_old");

		if ($DB->affectedRows() > 0) {
			makeNotice("Mining can added. I also popped " . $DB->affectedRows() . " Cans, " . "which were older than 2 hours.", "notice", "Old cans popped", "index.php?action=cans", "[ok]");
		}

		header("Location: index.php?action=cans");

	} else {
		makeNotice("Unable to create can in database:<br>" . $P->getMessage(), "error", "Internal error");
	}

}
?>
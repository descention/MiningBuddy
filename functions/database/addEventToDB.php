<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/addEventToDB.php,v 1.24 2008/01/02 20:01:32 mining Exp $
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

function addEventToDB() {

	global $MySelf;
	global $DB;

	// is the events module active?
	if (!getConfig("events")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}
	
	// Are we allowed to add Events?
	if (!$MySelf->canEditEvents()) {
		makeNotice("You are not allowed to add events!", "error", "Forbidden!");
	}

	// Do we have a short description?
	if (empty ($_POST[sdescr])) {
		makeNotice("You need to supply a short description!", "error", "Important field missing!");
	}

	// Do we have an officer?
	if (empty ($_POST[officer])) {
		makeNotice("You need to supply who is in command!", "error", "Important field missing!");
	}
	
	// Choose which supplied officer we use.
	if (!empty($_POST[officer2])) {
		sanitize($officer = sanitize($_POST[officer2]));
	} else {
		sanitize($officer = sanitize($_POST[officer]));
	}
	
	// Choose which system we use.
	if (!empty($_POST[system2])) {
		$system = strtolower($_POST[system2]);
	} else {
		$system = strtolower($_POST[system]);
	}
	
	// Check that we still have a valid systemname.
	if (empty($system)) {
		makeNotice("No valid Systemname found! Please go back, and try again.", "warning", "No system name",
		           "index.php?action=addevent", "[cancel]");
	}

	// Do we have an ETD?
	if (empty ($_POST[dur])) {
		makeNotice("You need to tell me the guessed runtime!", "error", "Important field missing!");
	}

	// Collateral?
	if (!is_numeric($_POST[collateral]) && $_POST[collateral] < 0) {
		makeNotice("You need to supply a valid collateral!", "error", "Important field missing!");
	}

	// Do we have an ETD?
	if ($_POST[payment] < 0) {
		makeNotice("You need to give the folks some money!", "error", "Important field missing!");
	}

	// Startting time goodness.
	$myTime = array (
		"day" => "$_POST[ST_day]",
		"month" => "$_POST[ST_month]",
		"year" => "$_POST[ST_year]",
		"hour" => "$_POST[ST_hour]",
		"minute" => "$_POST[ST_minute]",
		
	);
	$starttime = humanTime("toUnix", $myTime);

	// is the time valid?
	if (!$starttime) {
		makeNotice("Invalid time supplied!", "error", "Invalid Time!");
	}

	// Lets see what ships are required.
	$SHIPTYPES = array (
		"shuttles",
		"frigates",
		"destroyers",
		"cruisers",
		"bcruiser",
		"scruiser",
		"bship",
		"dread",
		"carrier",
		"titan",
		"barges",
		"indies",
		"freighter",
		"jfreighter",
		"exhumer"
	);
	foreach ($SHIPTYPES as $ship) {
		if ($_POST[$ship] == "on") {
			$wantedships[] = $ship;
		}
	}
	$ships = serialize($wantedships);

	$p = $DB->query("INSERT INTO events (sdesc, officer, system, security, type, starttime, " .
	"duration, difficulty, payment, collateral, notes, ships)
	               values (?,?,?,?,?,?,?,?,?,?,?,?)", array (
		sanitize($_POST[sdescr]),
		$officer,
		$system,
		sanitize($_POST[security]),
		sanitize($_POST[type]),
		sanitize($starttime),
		sanitize($_POST[dur]),
		sanitize($_POST[difficulty]),
		sanitize($_POST[payment]),
		sanitize($_POST[collateral]),
		sanitize($_POST[notes]),
		"$ships"
	));

	if ($DB->affectedRows() == 1) {

		// Prepare the announcement email.
		global $SITENAME;
		global $VERSION;
		global $URL;

		// Bloody hack to get latest ID. No one will ever know. ;)
		$lastID = $DB->getCol("SELECT max(ID) from events;");
		$risks = array (
			"No risk involved.",
			"Only inferior forces suspected.",
			"Somewhat risky.",
			"Moderate risk.",
			"Extreme risks are involved.",
			"No survivors expected."
		);
		$risk_index = $_POST[difficulty];

		// Fix the template up.
		$email = str_replace("{{ID}}", str_pad("$lastID[0]", "5", "0", STR_PAD_LEFT), getTemplate("newevent", "email"));
		$email = str_replace("{{SDESCR}}", $_POST[sdescr], $email);
		$email = str_replace("{{TYPE}}", $_POST[type], $email);
		// In case of a numeric value we have to translate that into plain english.
		if (is_numeric($_POST[officer])) {
			$officer = idToUsername($_POST[officer]);
		} else {
			$officer = sanitze($_POST[officer]);
		}
		$email = str_replace("{{FLAGOFFICER}}", ucfirst($officer), $email);
		$email = str_replace("{{SYSTEM}}", $_POST[system], $email);
		$email = str_replace("{{SECURITY}}", $_POST[security], $email);
		$email = str_replace("{{STARTTIME}}", date("d.m.y H:i:s", $starttime), $email);
		$email = str_replace("{{DURATION}}", $_POST[dur], $email);
		$email = str_replace("{{RISK}}", $risks[$risk_index], $email);
		$email = str_replace("{{PAYMENT}}", $_POST[payment], $email);
		$email = str_replace("{{COLLATERAL}}", number_format($_POST[collateral], 2), $email);
		$email = str_replace("{{NOTES}}", $_POST[notes], $email);
		$email = str_replace("{{SITENAME}}", $SITENAME, $email);
		$email = str_replace("{{URL}}", $URL, $email);
		$email = str_replace("{{VERSION}}", $VERSION, $email);

		// mail the user.
		mailUser($email, "New event added!");

		// Tell the admin what we did.
		makeNotice("Event added to the database and users who are opt-in got an email.", "notice", "New Event added.", "index.php?action=showevents", "[OK]");
	} else {
		makeNotice("Something went horribly wrong! AIEE!!", "error", "Mummy!");
	}

}
?>
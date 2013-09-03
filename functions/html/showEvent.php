<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showEvent.php,v 1.12 2008/01/02 20:01:32 mining Exp $
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

function showEvent() {

	// Lets import some globals, shall we?
	global $MySelf;
	global $DB;
	global $TIMEMARK;
	$ID = $MySelf->getID();
	
	// is the events module active?
	if (!getConfig("events")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}

	// Are we allowed to be here?
	if (!$MySelf->canSeeEvents()) {
		makeNotice("You are not allowed to do this!", "error", "Forbidden");
	}

	// Is the ID safe? 	
	if (!is_numeric($_GET[id]) || $_GET[id] < 0) {
		makeNotice("Invalid ID given!", "error", "Invalid Data");
	}

	// Load the event.
	$EVENTS = $DB->getRow("SELECT * FROM events WHERE id='$_GET[id]'");

	$mission = new table(2, true);
	$mission->addHeader(">> Mission information");
	$mission->addRow();
	$mission->addCol("Mission ID:");
	$mission->addCol(str_pad("$EVENTS[id]", 5, "0", STR_RIGHT_PAD));
	$mission->addRow();
	$mission->addCol("Mission Type:");
	$mission->addCol($EVENTS[type]);
	$mission->addRow();
	$mission->addCol("Executing Officer:");
	
	// In case of a numeric value we have to translate that into plain english.
	if (is_numeric($EVENTS[officer])) {
		$officer = idToUsername($EVENTS[officer]);
	} else {
		$officer = $EVENTS[officer];
	}
	$mission->addCol(ucfirst($officer));
	$mission->addRow();
	$mission->addCol("System:");
	$mission->addCol(ucfirst($EVENTS[system]));
	$mission->addRow();
	$mission->addCol("Security:");
	$mission->addCol($EVENTS[security]);

	// Has the event started yet?
	$delta = $TIMEMARK - $EVENTS[starttime];
	if ($delta > 0) {
		// Yep!
		$mission->addRow();
		$mission->addCol("Mission underway for:");
		$mission->addCol(numberToString($delta));
	} else {
		// Nope!
		$delta = $delta * -1;
		$mission->addRow();
		$mission->addCol("Mission will start in:");
		$mission->addCol(numberToString($delta));
	}

	$mission->addRow();
	$mission->addCol("Est. Duration:");
	$mission->addCol($EVENTS[duration]);

	// How difficult is it?
	$mission->addRow();
	$mission->addCol("Difficulty:");
	switch ($EVENTS[difficulty]) {

		case (0) :
			$mission->addCol("No risk involved");
			break;

		case (1) :
			$mission->addCol("Inferior forces");
			break;

		case (2) :
			$mission->addCol("Adequate forces");
			break;

		case (3) :
			$mission->addCol("Major forces expected");
			break;

		case (4) :
			$mission->addCol("Superior forces expected");
			break;

		case (5) :
			$mission->addCol("Suicide Mission");
			break;

	}

	$mission->addRow();
	$mission->addCol("Payment:");
	$mission->addCol($EVENTS[payment]);

	$mission->addRow();
	$mission->addCol("Collateral:");
	$mission->addCol(number_format($EVENTS[collateral]));

	$mission->addRow();
	$mission->addCol("Notes:");
	$mission->addCol(nl2br($EVENTS[notes]));

	$shipsTable = new table(3, true);
	$shipsTable->addHeader(">> Shiptypes and Joinups");

	// Compute the wanted Ships.
	$ships = unserialize($EVENTS[ships]);
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
	$TRANSLATE = array (
		"shuttles" => "Shuttle",
		"frigates" => "Frigate",
		"destroyers" => "Destroyer",
		"cruisers" => "Cruiser",
		"bcruiser" => "Battlecruiser",
		"scruiser" => "Strategic Cruiser",
		"bship" => "Battleship",
		"dread" => "Dreadnought",
		"carrier" => "Carrier",
		"titan" => "Titan",
		"barges" => "Mining Barge",
		"indies" => "Industrial Ship",
		"freighter" => "Freighter",
		"jfreighter" => "Jump Freighter",
		"exhumer" => "Exhumer"
	);

	$shipsTable->addRow("#060622");
	$shipsTable->addCol("Ship class");
	$shipsTable->addCol("Signed up");
	$shipsTable->addCol("Join up");

	// Ugh. ugly hack. Easier way?
	$JOINUPS_DS = $DB->getCol("SELECT signups FROM events WHERE id = '$_GET[id]'");
	$JOINUPS = unserialize($JOINUPS_DS[0]);
	unset ($JOINUPS_DS);
	$JOINUPS_SHIPS = array_count_values($JOINUPS);

	// Translate the ships.
	foreach ($SHIPTYPES as $type) {
		if (in_array($type, $ships)) {

			$shipsTable->addRow();
			$shipsTable->addCol($TRANSLATE[$type] . "s");

			// Print how many ships are coming.
			if ($JOINUPS_SHIPS[$type] != "") {
				$shipsTable->addCol("$JOINUPS_SHIPS[$type]");
			} else {
				$shipsTable->addCol("none");
			}

			// Okay this is fun. First lets see if the user is already in this event.
			if ($JOINUPS[$ID] != "") {
				// User in Event. Lets see if the current shiptype is the shiptype hes joined up with.
				if ($JOINUPS[$ID] != $type) {
					// Its not. Offer to switch.
					$shipsTable->addCol("<a href=\"index.php?action=joinevent&id=$EVENTS[id]&type=$type\">Switch to " . $TRANSLATE[$type] . " class</a>");
				} else {
					// It is. Renember him.
					$shipsTable->addCol("You are signed up as " . $TRANSLATE[$type]);
				}
			} else {
				// User is not in event, offer to joinup.
				$shipsTable->addCol("<a href=\"index.php?action=joinevent&id=$EVENTS[id]&type=$type\">Join as " . $TRANSLATE[$type] . "</a>");
			}
		}
	}

	// Offer to quit Event.
	if ($JOINUPS[$ID] != "") {
		$shipsTable->addHeaderCentered("<a href=\"index.php?action=joinevent&id=$EVENTS[id]&type=quit\">Cancel my signup for this event.</a>");
	}

	// Pilot overview.
	$pilotTable = new table(1, true);
	$pilotTable->addHeader(">> Current event roster");

	$keys = array_keys($JOINUPS);
	foreach ($keys as $key) {
		$pilotTable->addRow();
		$pilotTable->addCol(ucfirst(idToUsername($key)) . " has joined as a " . $TRANSLATE[$JOINUPS[$key]]);
	}

	// Return what we got.
	$html = "<h2>Detailed Mission Information</h2>" . $mission->flush();
	$html .= "<br>[<a href=\"index.php?action=showevents\">Back to overview</a>]<br>";
	$html .= "<br>" . $shipsTable->flush();
	$html .= "<br>" . $pilotTable->flush();

	return ($html);
}
?>
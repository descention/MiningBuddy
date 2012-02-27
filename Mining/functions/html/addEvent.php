<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/addEvent.php,v 1.21 2008/01/06 19:41:51 mining Exp $
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

function addEvent() {

	// Arr, we need some globals!
	global $MySelf;
	global $TIMEMARK;
	global $DB;

	// is the events module active?
	if (!getConfig("events")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}

	// Are we allowed to add an Event?
	if (!$MySelf->canEditEvents()) {
		makeNotice("You are not allowed to add events.", "error", "Forbidden");
	}

	// Create new tables, define standard mode.
	$table = new table(2, true);
	$mode = array (
		"align" => "right",
		"valign" => "top"
	);

	// Table header
	$table->addHeader(">> Announce an event");

	// Row: Short Description
	$table->addRow();
	$table->addCol("Short description:", $mode);
	$table->addCol("<input type=\"text\" name=\"sdescr\" size=\"50\" maxlength=\"50\">");

	// Row: Flag Officer
	$table->addRow();
	$table->addCol("Flag officer:", $mode);
	
	// Fetch all senior officers from the database
	$allSeniors = $DB->query("SELECT DISTINCT username, id FROM users WHERE isOfficial='1' AND deleted='0' ORDER BY username");
	
	// Loop through them.
	while ($senior = $allSeniors->fetchRow()) {
		// Pre-select ourselves.
		if ($senior[id] == $MySelf->getID()) {
			// Ourselves.
			$pdm .= "<option value=\"".$senior[id]."\" SELECTED>" . ucfirst($senior[username]) . "</option>";
		} else {
			// Some other senior officer.
			$pdm .= "<option value=\"".$senior[id]."\">" . ucfirst($senior[username]) . "</option>";
		}
	}
	// We dont need to check if we had any user matches at all: At least the user
	// Viewing this page is eligible.
	$pdm = "<select name=\"officer\">" . $pdm . "</select>";
	
	$table->addCol($pdm . " -or- <input type=\"text\" name=\"officer2\" maxlength=\"50\">");

	// We need $pdm soon enough again, clear it.
	unset($pdm);
	
	// Row: Location
	$table->addRow();
	$table->addCol("Location:", $mode);
	
	// Query all recent locations.
	$allLocations = $DB->query("SELECT DISTINCT system FROM events ORDER BY system");
	// Loop through them.
	while ($loc = $allLocations->fetchRow()) {
		// Add to dropdown.
		$pdm .= "<option>" . ucfirst($loc[system]) . "</option>";
		$haveLocations = true;
	}
	
	// If we never had an event, we wont show the recent locations dropdown menu.
	if ($haveLocations) {
		// Have recent locations
		$pdm = "<select name=\"system\">" . $pdm . "</select>";
		$table->addCol($pdm ." -or- <input type=\"text\" name=\"system2\" maxlength=\"50\">");
	} else {
		// No recent locations.
		$table->addCol("<input type=\"text\" name=\"system\" maxlength=\"50\">");
	}

	// We need $pdm soon enough again, clear it.
	unset($pdm);

	// Row: Security
	$table->addRow();
	$table->addCol("Security:", $mode);

	for ($i = 10; $i >= 0; $i--) {
		$security = number_format($i / 10, 1);
		$pdm .= "<option value=\"" . $security . "\">" . $security . "</option>";
	}
	$table->addCol("<select name=\"security\">" . $pdm . "</select>");

	// Row: Mission Type
	$table->addRow();
	$table->addCol("Mission Type:", $mode);
	$table->addCol("<select name=\"type\">" .
	"<option value=\"Mining\">Mining</option>" .
	"<option value=\"Mission\">Missions</option>" .
	"<option value=\"Kill\">Killing</option>" .
	"<option value=\"Transport\">Transporting</option>" .
	"<option value=\"PK\">Player Killing</option>" .
	"</select>");

	// Row: Starttime
	$table->addRow();
	$table->addCol("Starttime:", $mode);

	// Get a time-array and do the human friendly part.
	// Funnies: We always want to use "00" as the minute, and always at the start of the
	// NEXT hour.
	$times = humanTime("toHuman", ($TIMEMARK +3600));
	$happy_starting_time .= "<input type=\"text\" name=\"ST_day\"    size=\"4\" maxlength=\"20\" value=\"" . $times[day] . "\">.";
	$happy_starting_time .= "<input type=\"text\" name=\"ST_month\"  size=\"4\" maxlength=\"2\" value=\"" . $times[month] . "\">.";
	$happy_starting_time .= "<input type=\"text\" name=\"ST_year\"   size=\"6\" maxlength=\"4\" value=\"" . $times[year] . "\">";
	$happy_starting_time .= "&nbsp;&nbsp;";
	$happy_starting_time .= "<input type=\"text\" name=\"ST_hour\"   size=\"4\" maxlength=\"2\" value=\"" . date("H", ($TIMEMARK +3600)) . "\">:";
	$happy_starting_time .= "<input type=\"text\" name=\"ST_minute\" size=\"4\" maxlength=\"2\" value=\"00\">";
	$table->addCol($happy_starting_time);

	// Info for the startime
	$table->addRow();
	$table->addCol("");
	$table->addCol("(day.month.year hour:minute)");

	// Row: Expected Duration
	$table->addRow();
	$table->addCol("Expected Duration:", $mode);
	$table->addCol("<input type=\"text\" name=\"dur\" maxlength=\"20\" value=\"2 hours\">");

	// Row: Difficulty
	$table->addRow();
	$table->addCol("Difficulty:", $mode);
	$difficulty = "<option value=\"0\">No risk involved</option>";
	$difficulty .= "<option value=\"1\">Minimal risk</option>";
	$difficulty .= "<option value=\"2\">moderate risk</option>";
	$difficulty .= "<option value=\"3\">above average risk</option>";
	$difficulty .= "<option value=\"4\">extreme risk</option>";
	$difficulty .= "<option value=\"5\">No survivors expected</option>";
	$table->addCol("<select name=\"difficulty\">" . $difficulty . "</select>");

	// Row: Payment
	$table->addRow();
	$table->addCol("Payment:", $mode);
	$table->addCol("<input type=\"text\" name=\"payment\" maxlength=\"20\" value=\"0\">");

	// Row: Collateral
	$table->addRow();
	$table->addCol("Collateral:", $mode);
	$table->addCol("<input type=\"text\" name=\"collateral\" value=\"0\">");

	// Row: Notes
	$table->addRow();
	$table->addCol("Notes:", $mode);
	$table->addCol("<textarea name=\"notes\" rows=\"10\" cols=\"50\"></textarea>");

	// Row: Shipt types needed
	$table->addRow();
	$table->addCol("Ship types needed", array (
		"colspan" => 2
	));

	$table->addRow();
	$table->addCol("Shuttles");
	$table->addCol("<input type=\"checkbox\" name=\"shuttles\">");
	
	$table->addRow();
	$table->addCol("Frigates");
	$table->addCol("<input type=\"checkbox\" name=\"frigates\">");

	$table->addRow();
	$table->addCol("Destroyers");
	$table->addCol("<input type=\"checkbox\" name=\"destroyers\">");
	
	$table->addRow();
	$table->addCol("Cruisers");
	$table->addCol("<input type=\"checkbox\" name=\"cruisers\">");

	$table->addRow();
	$table->addCol("Battlecruisers");
	$table->addCol("<input type=\"checkbox\" name=\"bcruiser\">");

	$table->addRow();
	$table->addCol("Strategic Cruisers");
	$table->addCol("<input type=\"checkbox\" name=\"scruiser\">");
	
	$table->addRow();
	$table->addCol("Battleships");
	$table->addCol("<input type=\"checkbox\" name=\"bship\">");

	$table->addRow();
	$table->addCol("Dreadnoughts");
	$table->addCol("<input type=\"checkbox\" name=\"dread\">");
	
	$table->addRow();
	$table->addCol("Carriers");
	$table->addCol("<input type=\"checkbox\" name=\"carrier\">");
	
	$table->addRow();
	$table->addCol("Titans");
	$table->addCol("<input type=\"checkbox\" name=\"titan\">");
	
	$table->addRow();
	$table->addCol("Mining Barges");
	$table->addCol("<input type=\"checkbox\" name=\"barges\">");

	$table->addRow();
	$table->addCol("Industrial Ships");
	$table->addCol("<input type=\"checkbox\" name=\"indies\">");

	$table->addRow();
	$table->addCol("Freighters");
	$table->addCol("<input type=\"checkbox\" name=\"freighter\">");
	
	$table->addRow();
	$table->addCol("Jump Freighters");
	$table->addCol("<input type=\"checkbox\" name=\"jfreighter\">");
	
	$table->addRow();
	$table->addCol("Exhumers");
	$table->addCol("<input type=\"checkbox\" name=\"exhumer\">");
	
	// Submit button
	$form_end .= "<input type=\"submit\" name=\"submit\" value=\"Announce this event\">";
	$form_end .= "<input type=\"hidden\" name=\"action\" value=\"addevent\">";
	$form_end .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$table->addHeaderCentered($form_end, array (
		"align" => "center",
		"colspan" => 2
	));

	return ("<h2>Add event</h2><form action=\"index.php\" method=\"post\">" . $table->flush() . "</form>");
}
?>
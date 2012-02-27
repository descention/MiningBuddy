<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeCanPage.php,v 1.48 2008/01/02 20:01:32 mining Exp $
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

function makeCanPage() {

	// Defining some globals.
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	global $PREFS;
	$USERNAME = $MySelf->getUsername();
	$USERID = $MySelf->getID();
	$TTL = (getConfig("canLifeTime")*60);
	
	// is the cargo module active?
	if (!getConfig("cargocontainer")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}

	// Get all current locations.
	$locations = $DB->getCol("SELECT DISTINCT location FROM runs ORDER BY location");

	// Get all current cans.
	$cans = $DB->getAssoc("SELECT * from cans");

	// Get last can-nr.
	$canNaming = $PREFS->getPref("CanNaming");

	// Query the database accordingly.		
	if ($canNaming == 1) {
		$maxCan = $DB->getCol("SELECT MAX(name) as max FROM cans WHERE pilot = '$USERID'");
	} else {
		$maxCan = $DB->getCol("SELECT MAX(name) as max FROM cans");
	}

	// For can-naming: Increment the number.
	if ($maxCan[0] == "") {
		// No can jettisoned yet.
		$canname = "001";
	} else {
		if (is_numeric($maxCan[0])) {
			// Can ejected, and it is numeric, we can increase that number.
			$canname = str_pad(($maxCan[0] + 1), "3", "0", STR_PAD_LEFT);
		} else {
			// User entered some non-numerical stuff, can not increase.
			unset ($canname);
		}
	}

	// Get the system the users mining operation takes place in, if any.
	$myRun = userInRun($USERNAME);
	if ($myRun != false) {
		$myLocation = $DB->getCol("SELECT location FROM runs WHERE id='$myRun'");
		$myLocation = $myLocation[0];
	}

	// Assemble the locations dropdown menu.
	if (!empty ($locations)) {
		// Loop through all the locations.
		foreach ($locations as $location) {
			// And preselect the location the users miningrun takes place, if any.
			if ("$location" == "$myLocation") {
				$ddm .= "<option selected value=\"$location\">$location</option>";
			} else {
				$ddm .= "<option value=\"$location\">$location</option>";
			}
		}
	}

	// Select all current cans owned by the pilot.
	$CansDS = $DB->query("SELECT location, droptime, name, id, isFull, miningrun FROM cans WHERE pilot = '$USERID' ORDER BY droptime ASC");

	if ($CansDS->numRows() > 0) {

		// We have at least one can out there, lets do this.
		$myCans = new table(7, true);
		$myCans->addHeader(">> My cargo containers in space");
		$mode = array (
			"bold" => true
		);
		$myCans->addRow("#060622");
		$myCans->addCol("Name", $mode);
		$myCans->addCol("Location", $mode);
		$myCans->addCol("Self or Run", $mode);
		$myCans->addCol("Droptime", $mode);
		$myCans->addCol("est. Poptime", $mode);
		$myCans->addCol("Time Left", $mode);
		$myCans->addCol("Can is full", $mode);

		while ($can = $CansDS->fetchRow()) {

			$candroptime = $can[droptime]; // Time of can drop.
			$poptime = $candroptime + $TTL; // Extimated pop time (droptime + 1h)
			$timeleft = ($candroptime + $TTL) - $TIMEMARK; // Time left (poptime - current time)
			$minsleft = str_pad((number_format((($timeleft -60) / 60), 0)), "2", "0", STR_PAD_LEFT);
			$secsleft = str_pad(($timeleft % 60), "2", "0", STR_PAD_LEFT);

			if ($secsleft < 1) {
				// We want all negative amounts to read "00".
				$secsleft = "00";
			}

			// Colorize the remaining time
			if ($minsleft >= 30) {
				// More or equal 30 mins: Green. We are cool.
				$color = "#88ff88";
			}
			elseif ($minsleft <= 29 && $minsleft >= 15) {
				// Less or equal 29 mins: Yellow, keep an eye out.
				$color = "#FFFF00";
			}
			elseif ($minsleft < 15) {
				// Less than 15 minutes: Ayee! RED! Refresh!s
				$color = "#FF0000";
			}

			$myCans->addRow();
			$myCans->addCol("<a href=\"index.php?action=popcan&id=$can[id]\"><b>$can[name]</b></a>");
			
			$system = new solarSystem($can[location]);
			$myCans->addCol($system->makeFancyLink());

			// Can for self or mining run?
			if ($can[miningrun] >= 0) {
				$myCans->addCol("<a href=\"index.php?action=show&id=$can[miningrun]\">" . str_pad($can[miningrun], "5", "0", STR_PAD_LEFT) . "</a>");
			} else {
				$myCans->addCol("(for self)");
			}

			$myCans->addCol(date("H:i:s", $can[droptime]));
			$myCans->addCol(date("H:i:s", $poptime));

			// Can popped already?		 
			if ($minsleft > 0) {
				$myCans->addCol("<font color=\"$color\">" . numberToString($timeleft) . "</font>");
			} else {
				$myCans->addCol("<font color=\"$color\">POPPED</font>");
			}

			// Can full?
			if ($can[isFull]) {
				$myCans->addCol("<a href=\"index.php?action=togglecan&canid=$can[id]\"><font color=\"#00ff00\">YES</font></a>");
			} else {
				$myCans->addCol("<a href=\"index.php?action=togglecan&canid=$can[id]\">No</a>");
			}
		}

		// The delete all button.
		$myCans->addHeaderCentered("[<a href=\"index.php?action=popcan&id=all\">pop all cans</a>]");

		$MyCansExist = true;
	}

	// Select all current cans, belonging to the mining run.
	$MiningRun = userInRun($MySelf->getUsername());
	if ($MiningRun) {
		$CansDS = $DB->query("SELECT location, droptime, name, pilot, isFull, miningrun FROM cans WHERE miningrun='$MiningRun' ORDER BY droptime ASC");
		if ($CansDS->numRows() > 0) {
			// We got one or more can floating around that belong to our mining run.
			$runCans = new table(7, true);
			$runCans->addHeader(">> My operations's cargo containers in space");
			$runCans->addRow("#060622");
			$runCans->addCol("Name", $mode);
			$runCans->addCol("Owner", $mode);
			$runCans->addCol("Location", $mode);
			$runCans->addCol("Droptime", $mode);
			$runCans->addCol("est. Poptime", $mode);
			$runCans->addCol("time remaining", $mode);
			$runCans->addCol("is full", $mode);

			while ($can = $CansDS->fetchRow()) {
				// Same as above.
				$candroptime = $can[droptime];
				$timeleft = ($candroptime + $TTL) - $TIMEMARK;
				$minsleft = str_pad((number_format((($timeleft -60) / 60), 0)), "2", "0", STR_PAD_LEFT);
				$secsleft = str_pad(($timeleft % 60), "2", "0", STR_PAD_LEFT);
				$poptime = $candroptime + $TTL;

				// No negative minutes..
				if ($secsleft < 1) {
					$secsleft = "00";
				}

				// Colorize..
				if ($minsleft >= 30) {
					$color = "#88ff88";
				}
				elseif ($minsleft < 29 && $minsleft >= 15) {
					$color = "#FFFF00";
				}
				elseif ($minsleft < 15) {
					$color = "#FF0000";
				}

				// Build table..
				$runCans->addRow();
				$runCans->addCol($can[name]);
				$runCans->addCol(idToUsername($can[pilot]));
				
				$system = new solarSystem($can[location]);
				$runCans->addCol($system->makeFancyLink());
				
				$runCans->addCol(date("H:i:s", $can[droptime]));
				$runCans->addCol(date("H:i:s", $poptime));

				// Can popped already?		 
				if ($minsleft > 0) {
					$runCans->addCol("<font color=\"$color\">" . numberToString($timeleft) . "</font>");
				} else {
					$runCans->addCol("<font color=\"$color\">POPPED</font>");
				}

				// Can full?
				if ($can[isFull]) {
					$runCans->addCol("<font color=\"#00ff00\">YES</font>");
				} else {
					$runCans->addCol("No");
				}
			}
			$runCansExists = true;
		}
	}

	// Select all current cans, regardless
	$CansDS = $DB->query("SELECT location, droptime, name, pilot, isFull FROM cans WHERE pilot <> '$USERID' ORDER BY droptime ASC");

	if ($CansDS->numRows() > 0) {
		// There is at least.. yeah..
		$allCans = new table(7, true);
		$allCans->addHeader(">> All containers floating in space");
		$allCans->addRow("#060622");
		$allCans->addCol("Name", $mode);
		$allCans->addCol("Owner", $mode);
		$allCans->addCol("Location", $mode);
		$allCans->addCol("Droptime", $mode);
		$allCans->addCol("est. Poptime", $mode);
		$allCans->addCol("time remaining", $mode);
		$allCans->addCol("is full", $mode);

		while ($can = $CansDS->fetchRow()) {

			// Time-stuff, yet again.
			$candroptime = $can[droptime];
			$timeleft = ($candroptime + $TTL) - $TIMEMARK;
			$minsleft = str_pad((number_format((($timeleft -60) / 60), 0)), "2", "0", STR_PAD_LEFT);
			$secsleft = str_pad(($timeleft % 60), "2", "0", STR_PAD_LEFT);
			$poptime = $candroptime + $TTL;

			// no neg mins..
			if ($secsleft < 1) {
				$secsleft = "00";
			}

			// color..
			if ($minsleft >= 30) {
				$color = "#88ff88";
			}
			elseif ($minsleft < 29 && $minsleft >= 15) {
				$color = "#FFFF00";
			}
			elseif ($minsleft < 15) {
				$color = "#FF0000";
			}

			$allCans->addRow();
			$allCans->addCol($can[name]);
			$allCans->addCol(idToUsername($can[pilot]));
			
			$system = new solarSystem($can[location]);
			$allCans->addCol($system->makeFancyLink());
			
			$allCans->addCol(date("H:i:s", $can[droptime]));
			$allCans->addCol(date("H:i:s", $poptime));

			// Can popped already?		 
			if ($minsleft > 0) {
				$allCans->addCol("<font color=\"$color\">" . numberToString($timeleft) . "</font>");
			} else {
				$allCans->addCol("<font color=\"$color\">POPPED</font>");
			}

			// Can full?
			if ($can[isFull]) {
				$allCans->addCol("<font color=\"#00ff00\">YES</font>");
			} else {
				$CANS_other .= "<td align=\"center\">No</td>";
				$allCans->addCol("No");
			}
		}
		$allCansExists = true;
	}

	// Lets get down to html buisiness.

	// Show only what the man wants. Eh, Tony?
	global $PREFS;

	if ($PREFS->getPref("CanAddCans")) {
		// Create a new add-can table.
		$addFormTable = new table(2, true);
		$addFormTable->addHeader(">> Register a new cargo container");

		// Row: Name
		$addFormTable->addRow();
		$addFormTable->addCol("Container name:", $mode);
		$addFormTable->addCol("<input type=\"text\" name=\"cantag\" value=\"" . $canname . "\" maxlength=\"100\" size=\"20\">");

		// Row: Naming preferences
		$addFormTable->addRow();
		$addFormTable->addCol("Naming&nbsp;preferences:", $mode);

		// Pre-select the current preferences.
		switch ($canNaming) {
			case ("0") :
				$c1 = "selected";
				break;

			case ("1") :
				$c2 = "selected";
				break;

			case ("2") :
				$c3 = "selected";
				break;
		}

		$canNamingPDM = "<select name=\"canprefs\">" .
		"<option " . $c1 . " value=\"0\">Do not suggest names</option>" .
		"<option " . $c2 . " value=\"1\">Numbers - select your highest can-number</option>" .
		"<option " . $c3 . " value=\"2\">Numbers - select overall highest can-number</option>" .
		"</select>";

		$addFormTable->addCol($canNamingPDM);

		// Row: Location
		$addFormTable->addRow();
		$addFormTable->addCol("Location:", $mode);
		$addFormTable->addCol("<select name=\"location\">" . $ddm . "</select>");

		// Row: System
		$addFormTable->addRow();
		$addFormTable->addCol("<b>-or-</b> System name:", $mode);
		$addFormTable->addCol("<input type=\"text\" name=\"location2\">");

		// Row: Time of Launch
		$addFormTable->addRow();
		$addFormTable->addCol("Time of launch:", $mode);

		// Get a time-array and do the human friendly part.
		// Funnies: We always want to use "00" as the minute, and always at the start of the
		// NEXT hour.
		$times = humanTime("toHuman", $TIMEMARK);

		$timefield = "<input type=\"text\" name=\"ST_day\"    size=\"2\" maxlength=\"4\" value=\"" . $times[day] . "\">." .
		"<input type=\"text\" name=\"ST_month\"  size=\"2\" maxlength=\"4\" value=\"" . $times[month] . "\">." .
		"<input type=\"text\" name=\"ST_year\"   size=\"4\" maxlength=\"6\" value=\"" . $times[year] . "\">" .
		"&nbsp;&nbsp;" .
		"<input type=\"text\" name=\"ST_hour\"   size=\"2\" maxlength=\"4\" value=\"" . $times[hour] . "\">:" .
		"<input type=\"text\" name=\"ST_minute\" size=\"2\" maxlength=\"4\" value=\"" . $times[minute] . "\">";

		$addFormTable->addCol($timefield . " <i>(d:m:y, h:m)</i>");

		// Row: Belongs to run
		$addFormTable->addRow();
		$addFormTable->addCol("For mining op:", $mode);

		if ($PREFS->getPref("CanForRun")) {
			$addFormTable->addCol("<input type=\"checkbox\" CHECKED name=\"forRun\" value=\"true\"> Tick this if the can(s) you are dropping are part of your mining run, if any.");
		} else {
			$addFormTable->addCol("<input type=\"checkbox\" CHECKED name=\"forRun\" value=\"true\"> Tick this if the can(s) you are dropping are part of your mining run, if any.");
		}

		// Row: Submit button.
		$addFormTable->addHeaderCentered("<input type=\"submit\" name=\"create\" value=\"Register can in Database\">" .
		"<input type=\"hidden\" name=\"action\" value=\"addcan\">" .
		"<input type=\"hidden\" name=\"check\" value=\"true\">");
	}

	$html = "<h2>Cargo container chronograph</h2>";

	if ($PREFS->getPref("CanAddCans")) {
		$html .= "<form action=\"index.php\" method=\"post\">" . $addFormTable->flush();
	}

	if ($PREFS->getPref("CanMyCans") && $MyCansExist) {
		$html .= "<br>" . $myCans->flush();
	}

	if ($PREFS->getPref("CanRunCans") && $runCansExists) {
		$html .= "<br>" . $runCans->flush();
	}

	if ($PREFS->getPref("CanAllCans") && $allCansExists) {
		$html .= "<br>" . $allCans->flush();
	}

	return ($html . "</form>");

}
?>
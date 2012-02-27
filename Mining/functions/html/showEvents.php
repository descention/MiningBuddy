<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showEvents.php,v 1.19 2008/01/02 20:01:32 mining Exp $
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

function showEvents() {

	// Lets import some globals, shall we?
	global $MySelf;
	global $DB;
	global $TIMEMARK;
	$delta = $TIMEMARK -259200;
	
	// is the events module active?
	if (!getConfig("events")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}

	// Load all events.
	$EVENTS_DS = $DB->query("SELECT * FROM events WHERE starttime >= '" . $delta . "' ORDER BY starttime ASC");

	// .. right?
	if ($EVENTS_DS->numRows() >= 1) {

		// Lets keep in mind: We have events.
		$haveEvents = true;

		while ($event = $EVENTS_DS->fetchRow()) {

			// get the date.
			$date = date("d.m.y", $event[starttime]);

			// open up a new table for each day.
			if ($date != $previousdate) {

				$workday = date("l", $event[starttime]);

				if ($beenhere) {
					$html .= $temp->flush();
					$html .= "<br>";
				}

				$beenhere = true;

				// We need an additional row if we are allowed to delete events.
				if ($MySelf->canDeleteEvents()) {
					$temp = new table(8, true);
				} else {
					$temp = new table(7, true);
				}
				$temp->addHeader(">> Events for " . $workday . ", the " . $date);
				$previousdate = $date;

				$temp->addRow("#060622");
				$temp->addCol("ID");
				$temp->addCol("Starttime");
				$temp->addCol("Starts in / Runs for");
				$temp->addCol("Mission Type");
				$temp->addCol("Short Description");
				$temp->addCol("System");
				$temp->addCol("Security");
				if ($MySelf->canDeleteEvents()) {
					$temp->addCol("Delete");
				}
			}

			// Add Event to the current database.
			$temp->addRow();
			$temp->addCol("<a href=\"index.php?action=showevent&id=" . $event[id] . "\">" . str_pad($event[id], 4, "0", STR_PAD_LEFT) . "</a>");
			$temp->addCol(date("d.m.y H:i", $event[starttime]));

			$delta = $TIMEMARK - $event[starttime];
			if ($TIMEMARK > $event[starttime]) {
				// Event underway.
				$temp->addCol("<font color=\"#00ff00\">" . numberToString($delta) . "</font>");
			} else {
				// Event not started yet.
				$delta = $delta * -1;
				$temp->addCol("<font color=\"#ffff00\">" . numberToString($delta) . "</font>");
			}

			$temp->addCol($event[type]);
			$temp->addCol($event[sdesc]);
			$temp->addCol($event[system]);
			$temp->addCol($event[security]);
			
			if ($MySelf->canDeleteEvents()) {
				$temp->addCol("<a href=\"index.php?action=deleteevent&id=$event[id]\">delete event</a>");
			}
		}
	}

	// Lets recall, did we have events scheduled?
	if ($haveEvents) {
		// We do!			
		$html = "<h2>Scheduled Events</h2>" . $html . $temp->flush();
	} else {
		// We dont!			
		$html = "<h2>Scheduled Events</h2><b>There are currently no scheduled events in the database.</b>";
	}
	// Return what we got.
	return ($html);

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step6.php,v 1.15 2008/01/12 15:53:12 mining Exp $
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

if (getConfig("cargocontainer")) {
	$can_information = new table(8, true);
	$modes = array (
		"bold" => true
	);
	$can_information->addHeader(">> Cargo containers in space, belonging to this Op");

	if ($CansDS->numRows() > 0) {

		$TTL = getConfig("canLifeTime") * 60;
		// Table headers.
		$can_information->addRow("#060622");
		$can_information->addCol("Name of Can", $modes);
		$can_information->addCol("Owner of Can", $modes);
		$can_information->addCol("Location of Can", $modes);
		$can_information->addCol("Droptime", $modes);
		$can_information->addCol("est. Poptime", $modes);
		$can_information->addCol("Time left", $modes);
		$can_information->addCol("Can is full", $modes);

		// Are we allowed to pop cans?
		if ((userInRun($MySelf->getID(), $row[id]) && $MySelf->canAddHaul())) {
			$iCanPopCans = true;
		}

		// We are. Lets add a "pop can" column.
		if ($iCanPopCans) {
			$can_information->addCol("Pop can", $modes);
		} else {
			$can_information->addCol("", $modes);
		}

		// Loop through the cans.
		while ($can = $CansDS->fetchRow()) {

			// Calculate droptimes, poptimes et all.
			$candroptime = $can[droptime];
			$timeleft = ($candroptime + $TTL) - $TIMEMARK;
			$minsleft = str_pad((number_format((($timeleft -60) / 60), 0)), "2", "0", STR_PAD_LEFT);
			$secsleft = str_pad(($timeleft % 60), "2", "0", STR_PAD_LEFT);
			$poptime = $candroptime + $TTL;

			if ($secsleft < 1) {
				$secsleft = "00";
			}

			if ($minsleft >= 30) {
				$color = "#88ff88";
			}

			elseif ($minsleft < 29 && $minsleft >= 15) {
				$color = "#FFFF00";
			}

			elseif ($minsleft < 15) {
				$color = "#FF0000";
			}

			// Add the information to the table.
			$can_information->addRow();
			$can_information->addCol($can[name]);
			$can_information->addCol(ucfirst(idToUsername($can[pilot])));
			$system = new solarSystem($can[location]);
			$can_information->addCol(ucfirst($system->makeFancyLink()));
			$can_information->addCol(date("H:i:s", $can[droptime]));
			$can_information->addCol(date("H:i:s", $poptime));

			// Can popped already?		 
			if ($minsleft > 0) {
				$can_information->addCol("<font color=\"$color\">" . $minsleft . ":" . $secsleft . "</font>");
			} else {
				$can_information->addCol("<font color=\"$color\">POPPED</font>");
			}

			// Can full?
			if ($can[isFull]) {
				$can_information->addCol("<font color=\"#00ff00\">YES</font>");
			} else {
				$can_information->addCol("No");
			}

			// Offer a pop-can button if we are allowed to do so.
			if ($iCanPopCans) {
				$can_information->addCol("[<a href=\"index.php?action=popcan&id=$can[id]&runid=$row[id]\">pop</a>]");
			} else {
				$can_information->addCol("");
			}
		}
	} else {
		$CANS_run = false;
		$can_information->addRow("#060622");
		$can_information->addCol("There are Currently No Active Cans Assigned to this Op!", array (
	"bold" => true,
	"colspan" => 8,
	"align" => "center"
	));
	}
}
?>
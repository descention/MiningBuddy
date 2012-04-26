<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step2.php,v 1.38 2008/01/06 14:03:59 mining Exp $
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

// Create a new table for the general info.
$general_info = new table(2, true);

// Header
$general_info->addHeader(">> General Information");

// Row: Mining Run ID
$general_info->addRow();
$general_info->addCol("Mining ID:", $common_mode);
$general_info->addCol(str_pad($row['id'], 5, "0", STR_PAD_LEFT));

// Row: Is official run?
$general_info->addRow();
$general_info->addCol("This run is official:", $common_mode);
$general_info->addCol(yesno($row['isOfficial'], true));

// Row: Op Type
$general_info->addRow();
$general_info->addCol("Op Type:", $common_mode);
$general_info->addCol($row['optype']==""?"Standard":$row['optype']);

// Row: Supervisor Name
$general_info->addRow();
$general_info->addCol("Supervisor:", $common_mode);
$general_info->addCol(makeProfileLink($row['supervisor']));

// Row: Taxes
$general_info->addRow();
$general_info->addCol("Corp Taxes:", $common_mode);
$general_info->addCol($row['corpkeeps'] . ".0%");

// Row: Starttime
$general_info->addRow();
$general_info->addCol("Starttime:", $common_mode);
$general_info->addCol(date("d.m.y H:i", $row['starttime']));

// Row: Endtime

if ($row['endtime'] == "") {

	// Run is still open.
	$endtime = "ACTIVE";
	$general_info->addRow();
	$general_info->addCol("Endtime:", $common_mode);

	// Row: Endtime
	$time = numberToString($TIMEMARK - $row['starttime']);
	$secRunTime= $TIMEMARK - $row['starttime'];
	if ($time) {
		$general_info->addCol("<font color=\"#00ff00\">ACTIVE for " . numberToString($secRunTime) . "</font>");
	} else {
		$general_info->addCol("Event has not started yet.");
	}

	// Row: Corporation keeps %
	$general_info->addRow();
	$general_info->addCol("Corporation keeps:", $common_mode);
	$general_info->addCol("$row[corpkeeps]% of gross value.");

	// Current TMEC
	$general_info->addRow();
	$general_info->addCol("Current TMEC:");
	$general_info->addCol(calcTMEC($row['id'], true));

	// Statistical breakdown
	$totalISK = getTotalWorth($ID);
	if ($totalISK > 0) {
		$closed = $DB->getCol("SELECT endtime FROM runs WHERE id='" . $ID . "' LIMIT 1");
		if ($closed[0] < 1) {
			$general_info->addRow();
			$general_info->addCol("Total ISK so far:");
			$general_info->addCol(number_format($totalISK, 2) . " ISK");


			$general_info->addRow();
			$general_info->addCol("ISK per hour:");
			$general_info->addCol(number_format(($totalISK / ($secRunTime/60)) * 60) . " ISK");

		}

	}

	// Row: Actions
	$general_info->addRow();
	$general_info->addCol("Actions:", $common_mode);

	// Lets switch wether the user is currently in this run or not.
	$jointime = userInRun($MySelf->getUsername(), $ID);
	if ($jointime == "none") {
		// Is NOT in this run, give option to join.
		if (!runIsLocked($ID)) {
			if ($MySelf->canJoinRun()) {
				$join = "[<a href=\"index.php?action=joinrun&id=$ID\">Join this OP</a>]";
			} else {
				$join = "You are not allowed to join operations.";
			}
		} else {
			$join = (ucfirst(runSupervisor($ID)) . " has locked this run.");
		}
	} else {
		// User IS in this run.

		// Are we allowed to haul?
		if (($row['endtime'] == "") && ($MySelf->canAddHaul())) {
			$addHaul .= " [<a href=\"index.php?action=addhaul&id=$ID\">Haul</a>] ";
		} else {
			$addHaul .= false;
		}

		// Run-Owner: Lock/Unlock run (to dissallow people joining)
		if (runSupervisor($row['id']) == $MySelf->getUsername()) {
			if (runIsLocked($row['id'])) {
				$lock .= " [<a href=\"index.php?action=lockrun&id=$row[id]&state=unlock\">Unlock Run</a>] ";
			} else {
				$lock .= " [<a href=\"index.php?action=lockrun&id=$row[id]&state=lock\">Lock Run</a>] ";
			}
		}

		// IS in the run, give option to leave.
		$add .= " [<a href=\"index.php?action=partrun&id=$ID\">Leave Op</a>] [<a href=\"index.php?action=cans\">Manage Cans</a>]";
		//$add .= " [Leave Op Disabled] [<a href=\"index.php?action=cans\">Manage Cans</a>]";

		// Make the charity button.
		$charityFlag = $DB->getCol("SELECT charity FROM joinups WHERE run='$ID' AND userid='" . $MySelf->getID() . "' AND parted IS NULL LIMIT 1");
		if ($charityFlag[0]) {
			$charity = " [<a href=\"index.php?action=toggleCharity&id=$ID\">Unset Charity Flag</a>]";
		} else {
			$charity = " [<a href=\"index.php?action=toggleCharity&id=$ID\">Set Charity Flag</a>]";
		}
	}
	// Give option to end this op.
	if (($MySelf->getID() == $row['supervisor']) || ($MySelf->canCloseRun() && ($MySelf->isOfficial() || runSupervisor($row['id']) == $MySelf->getUsername()))) {
		$add2 = " [<a href=\"index.php?action=endrun&id=$ID\">Close Op</a>]";
	}

	// Refresh button.
	$refresh_button = " [<a href=\"index.php?action=show&id=$row[id]\">Reload page</a>]";
	$general_info->addCol($join . $addHaul . $add2 . $lock . $add . $charity . $refresh_button);

} else {
	// Mining run ended.

	// Row: Ended
	$general_info->addRow();
	$general_info->addCol("Ended on:", $common_mode);
	$general_info->addCol(date("d.m.y H:i", $row['endtime']));
	$ranForSecs = $row['endtime'] - $row['starttime'];

	// Duration
	$general_info->addRow();
	$general_info->addCol("Duration:", $common_mode);
	if ($ranForSecs < 0) {
		$general_info->addCol("Event was canceled before starttime.");
	} else {
		$general_info->addCol(numberToString($ranForSecs));
	}

	// Set flag for later that we dont generate active ship data.
	$DontShips = true;

	// Current TMEC
	$general_info->addRow();
	$general_info->addCol("TMEC reached:");
	$general_info->addCol(calcTMEC($row['id']), true);
}

// We have to check for "0" - archiac runs that have no ore values glued to them

if ($row['oreGlue'] > 0) {
	$general_info->addRow();
	$general_info->addCol("Ore Quotes:", $common_mode);

	// Is this the current ore quote?
	$cur = $DB->getCol("SELECT time FROM orevalues ORDER BY time DESC LIMIT 1");
	if ($cur[0] <= $row['oreGlue']) {
		// it is!
		$cur = "<font color=\"#00ff00\"><b>(current)</b></font>";
	} else {
		$cur = "<font color=\"#ff0000\"><b>(not using current quotes)</b></font>";
	}

	// Date of ore mod?
	//$modTime = $DB->getCol("SELECT time FROM orevalues WHERE time='" .  . "' LIMIT 1");
	$modDate = date("d.m.y", $row['oreGlue']);
	$general_info->addCol("[<a href=\"index.php?action=showorevalue&id=" . $row['oreGlue'] . "\">$modDate</a>] $cur");
}
//Edit Starts Here
// We have to check for "0" - archiac runs that have no ship values glued to them

if ($row['shipGlue'] > 0) {
	$general_info->addRow();
	$general_info->addCol("Ship Values:", $common_mode);

	// Are these the current Ship Values?
	$cur = $DB->getCol("SELECT id FROM shipvalues ORDER BY time DESC LIMIT 1");
	if ($cur[0] == $row['shipGlue']) {
		// it is!
		$cur = "<font color=\"#00ff00\"><b>(current)</b></font>";
	} else {
		$cur = "<font color=\"#ff0000\"><b>(not using current quotes)</b></font>";
	}

	// Date of ship mod?
	$modTime = $DB->getCol("SELECT time FROM shipvalues WHERE id='" . $row['shipGlue'] . "' LIMIT 1");
	$modDate = date("d.m.y", $modTime[0]);
	$general_info->addCol("[<a href=\"index.php?action=showshipvalue&id=" . $row['shipGlue'] . "\">$modDate</a>] $cur");
}
//Edit Ends Here
?>
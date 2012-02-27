<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step4.php,v 1.27 2008/01/06 14:03:59 mining Exp $
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

// Row: Joinups.

// Are we the supervisor of this run and/or an official?
if ((runSupervisor($ID) == $MySelf->getID()) || $MySelf->isOfficial()) {
	// We are.
	$join_info = new table(9, true);
	$icankick = true;
} else {
	// We are not.
	$join_info = new table(6, true);
}

$join_info->addHeader(">> Active Pilots");

if ($joinlog->numRows() > 0) {
	// Someone or more joined.

	$join_info->addRow("#060622");
	$join_info->addCol("Pilot", array (
		"bold" => true
	));
	$join_info->addCol("Joined", array (
		"bold" => true
	));
	$join_info->addCol("Active Time", array (
		"bold" => true
	));
	$join_info->addCol("State", array (
		"bold" => true
	));
	$join_info->addCol("Shiptype", array (
		"bold" => true
	));
	$join_info->addCol("Charity", array (
		"bold" => true
	));

	// Print the kick/ban/remove headers.
	if ($icankick) {
		$join_info->addCol("Remove", array (
			"bold" => true
		));
		$join_info->addCol("Kick", array (
			"bold" => true
		));
		$join_info->addCol("Ban", array (
			"bold" => true
		));
	}

	// Loop through all users who joined up.
	$gotActivePeople = false;

	while ($alog = $activelog->fetchRow()) {
		
		// People counter
		$activePeople++;
		
		$join_info->addRow();
		$join_info->addCol(makeProfileLink($alog[userid]));

		if ($TIMEMARK < $alog[joined]) {
			$join_info->addCol("request pending");
		} else {
			$join_info->addCol(date("H:i:s", $alog[joined]));
		}

		$time = numberToString($TIMEMARK - $alog[joined]);
		if ($time) {
			$join_info->addCol($time);
			$join_info->addCol("<font color=\"#00ff00\">ACTIVE</font>");
		} else {
			$join_info->addCol("request pending");
			$join_info->addCol("<font color=\"#FFff00\">PENDING</font>");
		}
		$join_info->addCol($SHIPTYPES[$alog[shiptype]]);

		$join_info->addCol(yesno($alog[charity], 1, 0));

		// Print the kick/ban/remove headers.
		if ($icankick) {
			if ($alog[userid] == $MySelf->getID()) {
				// Cant kick yourself.
				$join_info->addCol("---");
				$join_info->addCol("---");
				$join_info->addCol("---");
			} else {
				$join_info->addCol("[<a href=\"index.php?action=kickban&state=1&joinid=$alog[id]\">remove</a>]");
				//Edit start to remove kick/leave op
				$join_info->addCol("[<a href=\"index.php?action=kickban&state=2&joinid=$alog[id]\">kick</a>]");
				//$join_info->addCol("[disabled]");
				$join_info->addCol("[<a href=\"index.php?action=kickban&state=3&joinid=$alog[id]\">ban</a>]");
				//$join_info->addCol("[disabled]");
				//Edit End
			}
		}

		$gotActivePeople = true;
	}
	
	// Tell the folks how many active pilots we have, switching none, one or many.
	switch($join_info){
		case("0"):
			$join_info->addHeader("There are no active pilots.");
		break;
		
		case("1"):
			$join_info->addHeader("There is one pilot.");
		break;
		
		default:
			$join_info->addHeader("There are " . $activePeople . " active pilots.");	
		break;
	}
	

	/*
	 * Show what ships are currently online.
	 */
	if (!$DontShips) {
		$OnlineShips = $DB->query("SELECT count(shiptype) as count, shiptype FROM joinups WHERE run = '$ID' and parted is NULL GROUP BY shiptype");

		$shiptype_info = new table(2, true);
		$shiptype_info->addHeader(">> Active Ships");
		$shiptype_info->addRow("#060622");
		$shiptype_info->addCol("Shiptype", array (
			"bold" => true
		));
		$shiptype_info->addCol("Active count", array (
			"bold" => true
		));

		while ($ship_data = $OnlineShips->fetchRow()) {
			$shiptype = $ship_data[shiptype];
			$count = $ship_data[count];

			$shiptype_info->addRow();
			$shiptype_info->addCol($SHIPTYPES[$shiptype]);
			$shiptype_info->addCol($count . " active");
			$gotShips = true;
		}
	}

	/*
	 * Now that we know that there was at least ONE user who is active we can
	 * assemble a join and part log.
	 */

	$partlog_info = new table(7, true);
	$partlog_info->addHeader(">> Attendance Log");
	$partlog_info->addRow("#080822");
	$partlog_info->addCol("Pilot", array (
		"bold" => true
	));
	$partlog_info->addCol("Joined", array (
		"bold" => true
	));
	$partlog_info->addCol("Parted", array (
		"bold" => true
	));
	$partlog_info->addCol("Active Time", array (
		"bold" => true
	));
	$partlog_info->addCol("State", array (
		"bold" => true
	));
	$partlog_info->addCol("Shiptype", array (
		"bold" => true
	));
	$partlog_info->addCol("Notes", array (
		"bold" => true
	));

	while ($join = $joinlog->fetchRow()) {

		$partlog_info->addRow();
		$partlog_info->addCol(makeProfileLink($join[userid]));

		if ($TIMEMARK >= $join[joined]) {

			$partlog_info->addCol(date("H:i:s", $join[joined]));

			if ("$join[parted]" != "") {
				$partlog_info->addCol(date("H:i:s", $join[parted]));
				$partlog_info->addCol(numberToString((($join[parted] - $join[joined]))));
				$partlog_info->addCol("<font color=\"#ff0000\">INACTIVE</font>");
			} else {
				$partlog_info->addCol("<i>soon(tm)</i>");
				$partlog_info->addCol(numberToString((($TIMEMARK - $join[joined]))));
				$partlog_info->addCol("<font color=\"#00ff00\">ACTIVE</font>");
			}

			$partlog_info->addCol(joinAs($join[shiptype]));

		} else {
			$partlog_info->addCol("request pending");
			$partlog_info->addCol("request pending");
			$partlog_info->addCol("request pending");
			$partlog_info->addCol("request pending");
			$partlog_info->addCol(joinAs($join[shiptype]));
		}

		// Get the removal reason.
		switch ($join[status]) {
			default :
			case ("0") :
				$reason = " ";
				break;
			case ("1") :
				$reason = "removed by " . ucfirst(idToUsername($join[remover]));
				break;
			case ("2") :
				$reason = "<font color=\"#ffff00\">kicked</font> by " . ucfirst(idToUsername($join[remover]));
				break;
			case ("3") :
				$reason = "<font color=\"#ff0000\">banned</font> by " . ucfirst(idToUsername($join[remover]));
				break;
		}
		$partlog_info->addCol($reason);

	}

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/endRun.php,v 1.23 2008/01/02 20:01:32 mining Exp $
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

/*
* endrun();
* This ends the selected run.
*/
function endrun() {
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	
	// Is $_GET[id] truly a number?
	numericCheck($_GET[id]);

	// Are we allowed to close runs?
	$supervisor = $DB->getCol("SELECT supervisor FROM runs WHERE id='".$_GET[id]."' LIMIT 1");
	if (!$MySelf->canCloseRun() && ($MySelf->getID() != $supervisor[0])) {
		makeNotice("You are not allowed to close runs!", "error", "forbidden");
	}

	// We sure about this?
	confirm("Are you sure you want to close mining operation $_GET[id]? " . "This will remove any active pilots that are still on this" . " run, and close this run for good.");

	// Run already closed?
	if (!miningRunOpen($_GET[id])) {
		makeNotice("This mining operation has already been closed!", "warning", "Operation closed already", "index.php?action=show&id=$_GET[id]");
	}

	// Moved to the end of the payout to allow correct calculations
	// Update the database.
	//$DB->query("update runs set endtime = '$TIMEMARK' where id = '$_GET[id]' and endtime is NULL");

	// now "eject" all members.
	//$DB->query("update joinups set parted = '$TIMEMARK' where parted is NULL and run = '$_GET[id]'");

	// Calculate Payout, IF this is an official run.
	$ID = $_GET[id];
	$OfficialRun = $DB->getCol("SELECT isOfficial FROM runs WHERE id='$ID'");

	// calculate the total value of this op.
	$ISK = getTotalWorth($ID, true);

	

	if ($OfficialRun[0] && (getTotalWorth($ID) != 0)) {
		// Select all people, except banned ones.		
		$joinedPeople = $DB->query("SELECT DISTINCT userid FROM joinups WHERE run ='$ID' AND status < 2");

		// Also, create the charity array.
		$charityDB = $DB->query("SELECT userid, charity FROM joinups WHERE run ='$ID' AND status < 2");
		while ($c = $charityDB->fetchRow()) {
			$charityArray[$c[userid]] = $c[charity];
		}

		// get the payout array. Fun guaranteed.
		while ($peep = $joinedPeople->fetchRow()) {
			$payoutArray[$peep[userid]] = calcPayoutPercent($ID, $peep[userid]);
		}

		// Calulate the percent-modifier.
		$percentModifier = 100 / array_sum($payoutArray);

		// Apply the modifier to the percentage.
		$names = array_keys($payoutArray);

		// Add the credit.
		$supervisor = usernameToID(runSupervisor($_GET[id]));
		foreach ($names as $name) {
			$percent = $payoutArray[$name] * $percentModifier;
			$payout = ($ISK / 100) * $percent;
			// You cannot loose isk from a mission.
			if ($payout != 0 && !$charityArray[$name]) {
				addCredit($name, $supervisor, $payout, $_GET[id]);
				$finalPercent[$name]=$payout;
			}
		}
			// Moved to the end of the payout to allow correct calculations
		// Update the database.
		$DB->query("update runs set endtime = '$TIMEMARK' where id = '$ID' and endtime is NULL");

		// now "eject" all members.
		$DB->query("update joinups set parted = '$TIMEMARK' where parted is NULL and run = '$ID'");
		
		// wrap things up.
		makeEmailReceipt($ID, $finalPercent);
		makeNotice("The mining operation has ended. All still active pilots have been removed from the run and each pilot has been credited his share of the net income.", "notice", "Mining Operation closed", "index.php?action=list", "[OK]");
	}
	
		// Moved to the end of the payout to allow correct calculations
	// Update the database.
	$DB->query("update runs set endtime = '$TIMEMARK' where id = '$ID' and endtime is NULL");

	// now "eject" all members.
	$DB->query("update joinups set parted = '$TIMEMARK' where parted is NULL and run = '$ID'");
	// wrap things up.
	
	makeNotice("The mining operation has ended. All still active pilots have been removed from the run.", "notice", "Mining Operation closed", "index.php?action=list", "[OK]");

}
?>
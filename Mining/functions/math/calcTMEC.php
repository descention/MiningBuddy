<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/calcTMEC.php,v 1.8 2008/01/02 20:01:33 mining Exp $
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

function calcTMEC($runID, $force = false) {

	// We need the database.
	global $DB;

	// Check RunID for validity.
	numericCheck($runID, "0");

	if (!$force) {
		// Try to load a current TMEC.
		$TMEC = $DB->getCol("SELECT tmec FROM runs WHERE id='" . $runID . "'");
		$TMEC = $TMEC[0];

		// Got one, return that.
		if ($TMEC > 0) {
			return ($TMEC);
		}
	}

	// Calculate how long the op lasted.
	$times = $DB->query("SELECT * FROM runs WHERE id=" . $runID . " LIMIT 1");
	
	// Check that the run exists.
	if ($times->numRows() != 1) {
		// Doesnt. good thing we checked.
		return ("0");
	}
	
	$run = $times->fetchRow();
	
	// check that the endtime is valid.
	if ($run[endtime] == 0) {
		// Run still ongoing, pretent it ends now.
		global $TIMEMARK;
		$endtime = $TIMEMARK;
	} else {
		// Use real endtime.
		$endtime = $run[endtime];
	}
	
	// Calculate how many seconds the run lasted.
	$lasted = $endtime - $run[starttime];

	// Get the total ISK mined by the run.
	$ISK = getTotalWorth($runID);

	// Load PlayerCount.
	$playerCount = $DB->getCol("SELECT COUNT(DISTINCT userid) FROM joinups WHERE run='" . $runID . "'");
	$playerCount = $playerCount[0];

	// Calculate the TMEC.
	$TMEC = number_format(((($ISK / $lasted) / $playerCount) / 1000), 3);

	// Only positive TMECS
	if ($TMEC < 0) {
		$TMEC = 0;
	}

	if (!$force) {
		// Store the TMEC in the database.
		$DB->query("UPDATE runs SET tmec ='" . $TMEC . "' WHERE id='" . $runID . "' LIMIT 1");
	}

	return ($TMEC);

}
?>
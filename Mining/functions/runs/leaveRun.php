<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/leaveRun.php,v 1.12 2008/01/02 20:01:32 mining Exp $
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
 * This allows the user to leave a run.
 */

function leaveRun() {
	// Access the globals.    
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	$runid = $_GET[id];
	$userid = $MySelf->getID();

	// Are we actually still in this run?
	if (userInRun($userid, $runid) == "none") {
		makeNotice("You can not leave a run you are currently not a part of.", "warning", "Not you run.", "index.php?action=show&id=$runid", "[cancel]");
	}

	// Is $runid truly an integer?
	numericCheck($runid);

	// Oh yeah?
	if (runIsLocked($runid)) {
		confirm("Do you really want to leave mining operation #$runid ?<br><br>Careful: This operation has been locked by " . runSupervisor($runid, true) . ". You can not rejoin the operation unless its unlocked again.");
	} else {
		confirm("Do you really want to leave mining operation #$runid ?");
	}

	// Did the run start yet? If not, delete the request.
	$runStart = $DB->getCol("SELECT starttime FROM runs WHERE id='$runid' LIMIT 1");
	;

	if ($TIMEMARK < $runStart[0]) {
		// Event not started yet. Delete.
		$DB->query("DELETE FROM joinups WHERE run='$runid' AND userid='$userid'");
	} else {
		// Event started, just mark inactive.
		$DB->query("update joinups set parted = '$TIMEMARK' where run = '$runid' and userid = '$userid' and parted IS NULL");
	}

	makeNotice("You have left the run.", "notice", "You left the Op.", "index.php?action=show&id=$runid", "[OK]");
}
?>
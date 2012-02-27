<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/toggleLock.php,v 1.8 2008/01/02 20:01:32 mining Exp $
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

function toggleLock() {

	global $MySelf;

	// Check the ID for validity.
	if (!numericCheckBool($_GET[id], 0)) {
		makeNotice("That run ID is invalid.", "error", "Invalid RUN");
	} else {
		$ID = $_GET[id];
	}

	// Only the owner of the run can do this.
	if (runSupervisor($ID) != $MySelf->getUsername()) {
		makeNotice("Only the supervisor of a run can lock and unlock his/her run.", "warning", "Unable to comply", "index.php?action=show&id=$_GET[id]", "[Cancel]");
	}

	// Determine what the user wants.
	switch ($_GET[state]) {

		// User wants to lock.
		case ("lock") :
			confirm("You are about to lock Mining Operation #$ID. No one will be able to join up until you choose to unlock it. Is that what you want?");
			$bool = "1";
			break;

			// User wants to unlock.
		case ("unlock") :
			confirm("You are about to unlock Mining Operation #$ID. Everyone will be able to join up again until you choose to relock it. Is that what you want?");
			$bool = "0";
			break;

			// User wants to screw around.
		default :
			makeNotice("I dont know what you want off me. I only know lock and unlock. Sorry.", "warning", "Ehh?");
	}

	// Update the database!
	global $DB;
	$DB->query("UPDATE runs SET isLocked='$bool' WHERE id='$ID' LIMIT 1");

	// Success?
	if ($DB->affectedRows != 1) {
		header("Location: index.php?action=show&id=$ID");
	} else {
		makeNotice("Unable to set the new locked status in the database. Be sure to run the correct sql schema!", "warning", "Cannot write to database.");
	}

}
?>
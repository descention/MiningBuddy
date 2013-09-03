<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/toggleCharity.php,v 1.4 2008/05/01 15:37:24 mining Exp $
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
 * This simple function toggles the charity status.
 */

function toggleCharity() {
	// Some globals required.
	global $DB;
	global $MySelf;

	// Sanitize!
	$ID = sanitize($_GET[id]);

	// Mining run still open?
	if (!miningRunOpen($ID)) {
		makeNotice("You can not set the charity flag on closed operations!", "warning", "Failed", "index.php?action=show&id=$ID", "[Cancel]");
	}
	
	// update the flags
	$DB->query("UPDATE joinups SET charity=1 XOR charity WHERE userid='" . $MySelf->getID() . "' AND parted IS NULL AND run='" . $_GET[id] . "' LIMIT 1");

	// Check is we were successful.
	if ($DB->affectedRows() == 1) {
		// Load the new charity status.
		$newMode = $DB->getCol("SELECT charity FROM joinups WHERE userid='" . $MySelf->getID() . "' AND parted IS NULL AND run='" . $_GET[id] . "' LIMIT 1");
		if ($newMode[0]) {
			// He is now a volunteer.
			makeNotice("You have volunteered to waive your payout, and dontate it to your corporation. Thank you!", "notice", "Charity accepted", "index.php?action=show&id=" . $_GET[id]);
			header("Location: index.php?action=show&id=" . $_GET[id]);
		} else {
			// He is no longer a volunteer.
			makeNotice("You have revoked your waiver, you will recieve ISK for this run again.", "notice", "Charity revokation accepted", "index.php?action=show&id=" . $_GET[id]);
			header("Location: index.php?action=show&id=" . $_GET[id]);
		}
	} else {
		// Something went wrong with the database!
		makeNotice("Unable to set the charity flag!", "error", "Internal Error", "index.php?action=show&id=" . $_GET[id]);
	}
}
?>
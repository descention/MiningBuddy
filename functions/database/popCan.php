<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/popCan.php,v 1.9 2008/01/02 20:01:32 mining Exp $
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

function popCan() {

	// We need the globals, as always,
	global $DB;
	global $MySelf;

	$UserID = $MySelf->getID();

	// Is the ID sane?
	if ($_GET[id] != "all") {
		if (empty ($_GET[id]) || !is_numeric($_GET[id]) || $_GET[id] < 1) {
			makeNotice("Invalid container selected for popping!", "error");
		} else {
			$LIMIT = " AND id='$_GET[id]' LIMIT 1";
		}
	} else {
		confirm("Are you sure you want to pop all your cans?");
	}

	// Delete the can from the list.
	$DB->query("DELETE FROM cans WHERE pilot='$UserID' $LIMIT");

	// And tell the user what happened.
	$canspopped = $DB->affectedRows();

	// Do we want to go back to the run or the canpage?
	if (isset ($_GET[runid])) {
		$bl = "index.php?action=show&id=" . $_GET[runid];
	} else {
		$bl = "index.php?action=cans";
	}

	if ($canspopped == 1) {
		// ONE can has been popped.
		makeNotice("The can has been popped.", "notice", "POP!", $bl, "That was fun!");
	}
	elseif ($canspopped > 1) {
		// TWO OR MORE cans have been popped.
		makeNotice("$canspopped cans have been popped.", "notice", "POP!", $bl, "That was fun!");
	} else {
		// ZERO OR LESS cans have been popped.

		$col = $DB->getRow("SELECT id, pilot FROM cans WHERE id='$_GET[id]'");
		if (userInRun($MySelf->getID(), $col[id])) {
			$DB->query("DELETE FROM cans WHERE id='$col[id]' LIMIT 1");
			if ($DB->affectedRows() == 1) {
				makeNotice("You just popped a can belonging to " . idToUsername($col[pilot]) . ".", "notice", "POP!", $bl, "That was fun!");
			} else {
				makeNotice("The can could not be popped!", "error", "Internal Error", $bl, "[cancel]");
			}
		} else {
			makeNotice("The can could not be popped!", "error", "Internal Error", $bl, "[cancel]");
		}
	}
}
?>
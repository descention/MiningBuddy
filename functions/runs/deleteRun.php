<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/deleteRun.php,v 1.13 2008/01/02 20:01:32 mining Exp $
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

function deleteRun() {

	// We need some globals.
	global $DB;
	global $MySelf;
	global $READONLY;

	// Are we allowed to delete runs?
	if (!$MySelf->canDeleteRun() || $READONLY) {
		makeNotice("You are not allowed to delete runs!", "error", "forbidden");
	}

	// Set the ID.
	$ID = sanitize("$_GET[id]");
	if (!is_numeric($ID) || $ID < 0) {
		makeNotice("Invalid ID passed to deleteRun!", "error");
	}

	// Are we sure?
	confirm("Do you really want to delete run #$ID ?");

	// Get the run in question.
	$run = $DB->getRow("SELECT * FROM runs WHERE id = '$ID' LIMIT 1");

	// is it closed?
	if ("$run[endtime]" < "0") {
		makeNotice("You can only delete closed runs!", "error", "Deletion canceled", "index.php?action=list", "[cancel]");
	}

	// delete it.
	$DB->query("DELETE FROM runs WHERE id ='$ID'");

	// Also delete all hauls.
	$DB->query("DELETE FROM hauled WHERE miningrun='$ID'");

	// And joinups.
	$DB->query("DELETE FROM joinups WHERE runid='$ID'");

	makeNotice("The Miningrun Nr. #$ID has been deleted from the database and all associated hauls as well.", "notice", "Mining Operation deleted", "index.php?action=list", "[OK]");
}
?>
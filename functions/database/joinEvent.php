<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/joinEvent.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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

function joinEvent() {

	// Lets import some globals, why not.
	global $MySelf;
	global $DB;
	$ID = $MySelf->getID();

	// Are we allowed to be here?
	if (!$MySelf->canSeeEvents()) {
		makeNotice("You are not allowed to do this!", "error", "Forbidden");
	}

	// Is the ID safe? 	
	if (!is_numeric($_GET[id]) || $_GET[id] < 0) {
		makeNotice("Invalid ID given!", "error", "Invalid Data");
	}

	// Get the current list of members.
	$JOINS = $DB->getCol("SELECT signups FROM events WHERE id='$_GET[id]'");
	$JOINS = unserialize($JOINS[0]);

	// Add this ones ship.
	$JOINS[$ID] = sanitize($_GET[type]);

	// And store it back into the db.
	$p = $DB->query("UPDATE events SET signups = '" . serialize($JOINS) . "' WHERE ID='$_GET[id]' LIMIT 1");

	// Inform the user.
	if ($_GET[type] != "quit") {
		makeNotice("You have joined Event #$_GET[id]. Have fun, and dont be late!", "notice", "Joinup complete.", "index.php?action=showevent&id=$_GET[id]", "[OK]");
	} else {
		makeNotice("You have left Event #$_GET[id].", "notice", "Left Event", "index.php?action=showevent&id=$_GET[id]", "[OK]");
	}

}
?>
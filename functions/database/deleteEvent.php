<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/deleteEvent.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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

function deleteEvent() {

	// is the events module active?
	if (!getConfig("events")) {
		makeNotice("The admin has deactivated the events module.", "warning", "Module not active");
	}
	
	// Import the globals, as usual.
	global $DB;
	global $MySelf;

	// Are we allowed to be here?
	if (!$MySelf->canDeleteEvents()) {
		makeNotice("You are not allowed to do this!", "error", "Forbidden");
	}

	// Is the ID safe? 	
	if (!is_numeric($_GET[id]) || $_GET[id] < 0) {
		makeNotice("Invalid ID given!", "error", "Invalid Data");
	}

	// Does the user really want this?
	confirm("Are you sure you want to delete this event?");

	// Ok, then delete it.
	$DB->query("DELETE FROM events WHERE id = '$_GET[id]' LIMIT 1");

	if ($DB->affectedRows() == 1) {
		
			// Inform the people!
			// mailUser();
			
		makeNotice("The event has been deleted", "notice", "Event deleted", "index.php?action=showevents", "[OK]");
	} else {
		makeNotice("Could not delete the event from the database.", "error", "DB Error", "index.php?action=showevents", "[Cancel]");
	}
}
?>
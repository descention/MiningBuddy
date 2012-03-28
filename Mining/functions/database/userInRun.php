<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/userInRun.php,v 1.10 2008/01/02 20:01:32 mining Exp $
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
 * This function checks wether the user currently takes part in the
 * mining run. Required: Username and RunID.
 */

function userInRun($username, $run = "check") {

	// Get / Set important variables.
	global $DB;

	// If username is given, convert to ID.
	if (!is_numeric($username)) {
		$userID = usernameToID($username, "userInRun");
	} else {
		$userID = $username;
	}

	// Is $run truly an integer?
	if ($run != "check") {
		// We want to know wether user is in run X.
		numericCheck($run);
	} else {
		// We want to know if user is in any run, and if so, in which one.
		$results = $DB->getCol("select run from joinups where userid = '$userID' and parted is NULL limit 1");

		// Return false if in no run, else ID of runNr.
		if ($results[0] == "") {
			return (false);
		} else {
			return ($results[0]);
		}
	}

	// Query the database and return wether he is in run X or not.
	$results = $DB->query("select joined from joinups where userid in (select id from users where authID in (select distinct authID from users where id = '$userID')) and run = '$run' and parted is NULL limit 1");
	
	if ($results->numRows() == 0) {
		return ("none");
	} else {
		while ($row = $results->fetchRow()) {
			return ($row[joined]);
		}
	}
}
?>
<?php


/*
* MiningBuddy (http://miningbuddy.net)
* $Header: /usr/home/mining/cvs/mining/functions/system/checkForUpdate.php,v 1.1 2008/01/03 14:55:11 mining Exp $
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
 * This function calls home, anonymously, and gets the current
 * release version.
 */


function checkForUpdate() {
	/*This function is disabled till it can be fixed.
	// We need global stuff and some special vars.
	global $DB;
	global $VERSION_COMP;
	global $IS_BETA;
	global $IS_HOSTED;
	global $URL;
	global $VERSION;

	// No update necessary for hosted sites, really.
	if ($IS_HOSTED) {
		return;
	}

	// We need current time, not eve time.
	$TIME = date("U");

	// First, we check the database for a cached versionumber.
	$cachedResult = $DB->query("SELECT name, value FROM config WHERE name='newVersion' LIMIT 1");
	if ($cachedResult->numRows() == 0) {
		// Ok, so we have *NO* cache result, lets check when we did call the server
		$lastCheck = $DB->getCol("SELECT value FROM config WHERE name='lastCheck'");
		// Only call (max) every 6h:
		$lastCheck = $lastCheck[0] + 21600;

		if ($TIME >= $lastCheck) {
			// We last called 6 hours or more ago, or never. Get new version.
			if ($IS_BETA) {
				$updatefile = "version-beta.txt";
			} else {
				$updatefile = "version.txt";
			}

			// open Connection to my server, get the version file.
			$fsock = @ fsockopen('update.miningbuddy.net', 80);
			if ($fsock) {

				// Sent HTTP Headers
				@ fputs($fsock, "GET /$updatefile HTTP/1.1\r\n");
				@ fputs($fsock, "HOST: update.miningbuddy.net\r\n");
				@ fputs($fsock, "Referer: $URL\r\n");
				@ fputs($fsock, "User-Agent: $VERSION\r\n");
				@ fputs($fsock, "Connection: close\r\n\r\n");

				// Temp var used.
				$get_info = false;

				// Assemble the remote Version
				while (!@ feof($fsock)) {
					if ($get_info) {
						$serverVersion = (fread($fsock, 1024));
					}
					elseif (@ fgets($fsock, 1024) == "\r\n") {
						$get_info = true;
					}
				}
			}
			@ fclose($fsock);
			$serverVersion = str_replace("\n", "", $serverVersion);

			// Did we get a new version?
			if ($serverVersion != "") {
				// We did, save it into the database.
				$DB->query("INSERT INTO config (name, value) VALUES (?,?)", array (
					"newVersion",
					"$serverVersion"
				));
				$DB->query("INSERT INTO config (name, value) VALUES (?,?)", array (
					"lastCheck",
					date("U")
				));
			} else {
				// Something went wrong, just say no update available. No one will ever know.
				return;
			}
		}
	} else {
		// We are using a cached result then.
		$lastCheck = $DB->getCol("SELECT value FROM config WHERE name='lastCheck'");
		// Lets refresh, just in case more versions have been released.
		$lastCheck = $lastCheck[0] + 86400;
		if ($TIME < $lastCheck) {
			$versionRow = $cachedResult->fetchRow();
			$serverVersion = $versionRow[value];
		} else {
			// ANOTHER new version out, delete cache.
			$DB->query("DELETE FROM config WHERE name='newVersion'");
			$DB->query("DELETE FROM config WHERE name='lastCheck'");
		}
	}

	// Compare the versions.
	$status = version_compare($serverVersion, $VERSION_COMP);

	// New version available!
	if ($status > 0) {
		$table = "<table align=\"center\" bgcolor=\"#682000\">";
		$table .= "<tr>";
		$table .= "<td><b>Update available!</b></td>";
		$table .= "</tr>";
		$table .= "<tr>";
		$table .= "<td>Version $serverVersion out now!</td>";
		$table .= "</tr>";
		$table .= "</table><br>";
	}
	return ($table);
	*/
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/modProfile.php,v 1.1 2008/01/06 14:03:59 mining Exp $
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

function modProfile() {

	// The usual suspects.
	global $MySelf;
	global $DB;

	$ID = $MySelf->getID();

	// Load the profile.
	$profile = new profile($ID);
	$username = ucfirst(idToUsername($ID));

	if (isset ($_POST[about])) {
		// Check that its "US".
		if ($ID != $_POST[id]) {
			makeNotice("You can only change your own profile.", "warning", "Permission denied", "index.php?action=profile&id=$ID");
		}
		// Set the POST about
		$profile->setAbout(sanitize($_POST[about]));
	} else {
		// Check that its "US".
		if ($ID != $_GET[id]) {
			makeNotice("You can only change your own profile.", "warning", "Permission denied", "index.php?action=profile&id=$ID");
		}
		
		// Set mining flag safely.
		if (isset ($_GET[mining])) {
			switch ($_GET[mining]) {
				case ("true") :
					$profile->setMiner(true);
					break;
				case ("false") :
					// Set to false.
					$profile->setMiner(false);
					break;
				default :
					// Something is not right here.
					makeNotice("You can only choose true or false for the miner flag!", "error", "Invalid flag!");
					break;
			}

		}

		// Set hauling flag safely.
		if (isset ($_GET[hauling])) {
			switch ($_GET[hauling]) {
				case ("true") :
					$profile->setHauler(true);
					break;
				case ("false") :
					// Set to false.
					$profile->setHauler(false);
					break;
				default :
					// Something is not right here.
					makeNotice("You can only choose true or false for the hauler flag!", "error", "Invalid flag!");
					break;
			}

		}

		// Set fighting flag safely.
		if (isset ($_GET[fighting])) {
			switch ($_GET[fighting]) {
				case ("true") :
					$profile->setFighter(true);
					break;
				case ("false") :
					// Set to false.
					$profile->setFighter(false);
					break;
				default :
					// Something is not right here.
					makeNotice("You can only choose true or false for the fighter flag!", "error", "Invalid flag!");
					break;
			}

		}

		// Set email flag safely.
		if (isset ($_GET[email])) {
			switch ($_GET[email]) {
				case ("show") :
					$profile->setEmailShown(true);
					break;
				case ("hide") :
					// Set to false.
					$profile->setEmailShown(false);
					break;
				default :
					// Something is not right here.
					makeNotice("You can only choose true or false for the email flag!", "error", "Invalid flag!");
					break;
			}

		}
	}

	// Silently return to the profile page.	
	header("Location: index.php?action=profile&id=" . $MySelf->getID());
}
?>
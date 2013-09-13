<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/editUser.php,v 1.2 2008/05/01 15:37:24 mining Exp $
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

function editUser() {

	// We need global variables and object.
	global $DB;
	global $MySelf;
	global $IS_DEMO;

	if ($IS_DEMO && $_POST['id'] == "1") {
		makeNotice("The user would have been changed. (Operation canceled due to demo site restrictions.)", "notice", "Password change confirmed");
	}

	// Are we allowed to Manage Users?
	if (!$MySelf->canManageUser()) {
		makeNotice("You are not allowed to edit Users!", "error", "forbidden");
	}

	// Sanitize the ID
	$ID = sanitize($_POST['id']);
	$SELF = $MySelf->getID();

	if (!is_numeric($ID)) {
		// Yikes! Non-Number!
		makeNotice("Variable is not numeric! (in editUser)", "error");
	}

	// Load the dataset.
	$userDS = $DB->query("SELECT * FROM users WHERE id='$ID' LIMIT 1");
	$user = $userDS->fetchRow();

	// Non-admin tries to edit an admin, err no.
	if ($user['isAdmin'] && !$MySelf->isAdmin()) {
		makeNotice("Only an Administrator may edit another Administrator. You do have the rights to edit users, but you are not allowed to modify an Administrators account.", "warning", "Insufficient rights!", "index.php?action=edituser&id=$ID", "Cancel");
	}

	// Do we want to delete the user?
	if ($_POST['delete'] == "true") {
		if ($ID == $SELF) {
			makeNotice("You can not delete yourself! Why would you do such a thing? " .
			"Life is not that bad, c'mon...'", "warning", "Operation canceled", "index.php?action=edituser&id=$ID", "get yourself together, man");
		}

		// Are we allowed to delete users?
		if (!$MySelf->canDeleteUser()) {
			makeNotice("You are not authorized to do that!", "error", "Forbidden");
		}

		// Get confirmation
		confirm("You are about to delete " . ucfirst(idToUsername($ID)) . ". Are you sure?");

		$DB->query("UPDATE users SET deleted='1' WHERE id='$ID' LIMIT 1");
		if ($DB->affectedRows() == 1) {
			makeNotice("The Account has been deleted.", "notice", "Account deleted", "index.php?action=editusers", "Back to editing Users");
		} else {
			makeNotice("Error deleting the user!", "error");
		}
	}

	// Activate the account, or disable it.
	if ($_POST['canLogin'] == "on") {
		$DB->query("UPDATE users SET active='1' WHERE id ='$ID' LIMIT 1");
	} else {
		if ($ID == $SELF) {
			makeNotice("You can not deactivate yourself!", "error", "Err..", "index.php?action=edituser&id=$ID", "Back to yourself ;)");
		} else {
			$DB->query("UPDATE users SET active='0' WHERE id ='$ID'");
		}
	}

	// Confirm the account.
	if ($_POST['confirm'] == "true") {
		$DB->query("UPDATE users SET confirmed='1' WHERE id ='$ID' LIMIT 1");
		lostPassword($user['username']);
		$ADD = " Due to confirmation I have sent an email to the user with his password.";
	}

	// Force the users email to be valid.
	if ($_POST['SetEmailValid'] == "true") {
		$DB->query("UPDATE users SET emailvalid='1' WHERE id ='$ID' LIMIT 1");
	}

	global $IS_DEMO;
	if (!$IS_DEMO) {
		// Set the new email.
		if (!empty ($_POST['email'])) {
			$email = sanitize($_POST['email']);
			$DB->query("UPDATE users SET email='$email' WHERE id ='$ID'");
		}

		// Set the new Password.
		if (!empty ($_POST['password'])) {
			$password = encryptPassword(sanitize($_POST['password']));
			$DB->query("UPDATE users SET password='$password' WHERE id ='$ID'");
		}

		// Change (shudder) the username.
		if ($_POST[username_check] == "true" && $_POST['username'] != "") {
			if ($MySelf->isAdmin() && $MySelf->canManageUser()) {
				// Permissions OK.
				$new_username = sanitize($_POST['username']);

				// Check for previously assigned username
				$count = $DB->getCol("SELECT COUNT(username) FROM users WHERE username='$new_username'");
				if ($count[0] > 0) {
					// Username exists already.
					makeNotice("The new username \"$new_username\" already exists. Unable to complete operation.", "error", "Username exists!");
				} else {
					// Username free. Update DB.
					$DB->query("UPDATE users SET username='" . $new_username . "' WHERE ID='" . $ID . "' LIMIT 1");

					// Check for failure, not success.
					if ($DB->affectedRows() != 1) {
						// Something is wrong :(
						makeNotice("DB Error: Internal Error: Unable to update the username.", "error", "Internal Error");
					}
				}

			} else {
				// Insufficient permissions
				makeNotice("Inusfficient rights to change username.", "error", "Insufficient Rights");
			}
		}
	}

	// Are we allowed to edit ranks?
	if ($MySelf->canEditRank()) {

		// Set the new Rank.
		if (is_numeric($_POST['rank']) && $_POST['rank'] >= 0) {
			$rank = sanitize($_POST['rank']);
			$DB->query("UPDATE users SET rank='$rank' WHERE id ='$ID'");
		}

		// toggle the opt-in setting.
		// Its a checkbox. So we have to endure the pain.
		if ($_POST['optIn']) {
			$state = 1;
		} else {
			$state = 0;
		}
		$DB->query("UPDATE users SET optIn='$state' WHERE id='$ID' LIMIT 1");

		// Do the permissions.
		$permissions = array (
			"canLogin",
			"canJoinRun",
			"canCreateRun",
			"canCloseRun",
			"canDeleteRun",
			"canAddHaul",
			"canChangePwd",
			"canChangeEmail",
			"canChangeOre",
			"canAddUser",
			"canSeeUsers",
			"canDeleteUser",
			"canEditRank",
			"canManageUser",
			"canSeeEvents",
			"canEditEvents",
			"canDeleteEvents",
			"isLottoOfficial",
			"canPlayLotto",
			"isOfficial",
			"isAdmin",
			"isAccountant",
			
		);

		// Loop through each of the resources.
		foreach ($permissions as $perm) {

			// Convert the html "on" to "1" and "0", respectively
			if ($_POST[$perm] == "on") {
				$state = "1";
			} else {
				$state = "0";
			}

			// Update the database.
			$DB->query("UPDATE users SET $perm='$state' WHERE id ='$ID'");
		}
	}

	makeNotice("User data has been updated. $ADD", "notice", "User updated", "index.php?action=edituser&id=$ID", "[OK]");
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/addNewUser.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
* addNewUser();
* This adds a new user to the database.
*/
function addNewUser() {
	// globals
	global $DB;
	global $MySelf;

	// Sanitize the input.
	$USERNAME = $MySelf->getUsername;
	$NEW_USER = strtolower(sanitize($_POST[username])); // supplied new username.
	
	if (!ctypeAlnum($NEW_USER)) {
		makeNotice("Only characters a-z, A-Z and 0-9 are allowed as username.", "error", "Invalid Username");
	}

	/* Password busines */
	if ($_POST[pass1] != $_POST[pass2]) {
		makeNotice("The passwords did not match!", "warning", "Passwords invalid", "index.php?action=newuser", "[retry]");
	}

	$PASSWORD = encryptPassword("$_POST[pass1]");
	$PASSWORD_ENC = $PASSWORD;

	/* lets see if the users (that is logged in) has sufficient
	* rights to create even the most basic miner. Level 3+ is
	* needed.
	*/
	if (!$MySelf->canAddUser()) {
		makeNotice("You are not authorized to do that!", "error", "Forbidden");
	}

	// Lets prevent adding multiple users with the same name.
	if (userExists($NEW_USER) >= 1) {
		makeNotice("User already exists!", "error", "Duplicate User", "index.php?action=newuser", "[Cancel]");
	}

	// So we have an email address?
	if (empty ($_POST[email])) {
		// We dont!
		makeNotice("You need to supply an email address!", "error", "Account not created");
	} else {
		// We do. Clean it.
		$NEW_EMAIL = sanitize($_POST[email]);
	}

	// Inser the new user into the database!
	$DB->query("insert into users (username, password, email, addedby, confirmed) " .
	"values (?, ?, ?, ?, ?)", array (
		"$NEW_USER",
		"$PASSWORD_ENC",
		"$NEW_EMAIL",
	$MySelf->getUsername(), "1"));

	// Were we successfull?
	if ($DB->affectedRows() == 0) {
		makeNotice("Could not create user!", "error");
	} else {
		// Write the user an email.
		global $SITENAME;
		$mail = getTemplate("newuser", "email");
		$mail = str_replace('{{USERNAME}}', "$NEW_USER", $mail);
		$mail = str_replace('{{PASSWORD}}', "$PASSWORD", $mail);
		$mail = str_replace('{{SITE}}', "http://$_SERVER[HTTP_HOST]/", $mail);
		$mail = str_replace('{{CORP}}', "$SITENAME", $mail);
		$mail = str_replace('{{CREATOR}}', "$USERNAME", $mail);
			$to = $NEW_EMAIL;
			$DOMAIN = $_SERVER[HTTP_HOST];
			$subject = "Welcome to MiningBuddy";
			$from = "MiningBuddy@" . $DOMAIN;
			$headers = "From:" . $from;
			mail($to,$subject,$mail,$headers);
		//		mail($NEW_EMAIL, "Welcome to MiningBuddy", $mail);
		makeNotice("User added and confirmation email sent.", "notice", "Account created", "index.php?action=editusers");
	}

}
?>
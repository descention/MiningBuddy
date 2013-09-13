<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/usermngt/requestAccount.php,v 1.4 2008/10/22 12:15:15 mining Exp $
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
* requestAccount();
* This adds a new user to the database, waiting to be confirmed.
*/
function requestAccount() {
	// globals
	global $DB;
	global $MySelf;
	global $TIMEMARK;
	global $MB_EMAIL;

	// Generate random Password
	$PASSWORD = base64_encode(rand(111111111111, 999999999999));
	$PASSWORD_ENC = encryptPassword($PASSWORD);

	// Sanitize the input.
	$NEW_USER = strtolower(sanitize($_POST['username'])); // supplied new username.

	// Lets prevent adding multiple users with the same name.
	if (userExists($NEW_USER)) {
		makeNotice("Your account was not created because there is already an account with the same username. Please pick another. " . "If you forgot your password, please use the password recovery link on the login page.", "error", "Account not created");
	}

	// So we have a username?
	if (strlen($_POST['username']) < 3) {
		makeNotice("Your username must be longer than 3 letters.", "error", "Invalid Username");
	}

	// Let me rephrase: Do we have a VALID username?
	if (!ctypeAlnum($_POST['username'])) {
		makeNotice("Only characters a-z, A-Z, 0-9 and spaces are allowed as username.", "error", "Invalid Username");
	}

	// So we have an email address?
	if (empty ($_POST['email'])) {
		// We dont!
		makeNotice("You need to supply an email address!", "error", "Account not created");
	} else {
		// We do. Clean it.
		$NEW_EMAIL = sanitize($_POST['email']);

		// Valid one, too?
		if (!checkEmailAddress($NEW_EMAIL)) {
			makeNotice("You need to supply a valid email address!", "error", "Account not created");
		}
	}

	// Is it the very first account?
	$count = $DB->query("SELECT * FROM users");
	if ($count->numRows() == 0) {

		$temp = $DB->query("INSERT INTO `users` (`username`, `password`, `email`, `addedby`," .
				" `lastlogin`, `confirmed`, `emailvalid`, `emailcode`, `optIn`, `canLogin`," .
				" `canJoinRun`, `canCreateRun`, `canCloseRun`, `canDeleteRun`, `canAddHaul`," .
				" `canChangePwd`, `canChangeEmail`, `canChangeOre`, `canAddUser`, `canSeeUsers`," .
				" `canDeleteUser`, `canEditRank`, `canManageUser`, `canEditEvents`, `canDeleteEvents`," .
				" `canSeeEvents`, `isOfficial`, `isLottoOfficial`, `isAccountant`, `preferences`, `isAdmin`, `rank`) " .
		"VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array (
			stripcslashes($NEW_USER), $PASSWORD_ENC, $NEW_EMAIL, 1, 1, 1, 1, 1, 1, 1, 1, 1,
		    1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1));

		// Check for success, catch database errors.
		if (gettype($temp) != "DB_Error" && ($DB->affectedRows() == 1)) {

			// Success! New superuser created, send a confirmation email.
			$email = "Superuser information: Username " . stripcslashes($NEW_USER) . ", Password $PASSWORD - change this as soon as possible!";
			global $VERSION;
			$headers = "From:" . $MB_EMAIL;
			global $MAIL;
			if(isset($MAIL)){
				$MAIL->AddAddress($NEW_EMAIL);
				$MAIL->Subject = "Superuser login information (" . $VERSION . ")";
				$MAIL->Body = $email;
				$MAIL->Send();
			}else{
				mail("$NEW_EMAIL", "Superuser login information (" . $VERSION . ")", $email, $headers);
			}
			unset ($email);

			// Inform the user.
			makeNotice("New Superuser created:<br>Username: " . stripcslashes($NEW_USER) . "<br>Password: $PASSWORD");

		} else {

			// Something went wrong!
			makeNotice("Failed creating the superuser!<br><br>" . $temp->getMessage(), "error", "Database Error!");

		}

	} else {

		// Lets avoid multiple accounts per email address!
		$otherAccsDS = $DB->query("SELECT COUNT(email) AS count FROM users WHERE email = '$NEW_EMAIL' ");
		$otherAccs = $otherAccsDS->fetchRow();

		if ($otherAccs[count] > 0) {
			makeNotice("There is already an account with your supplied eMail address. If you lost " . "your password please  use the password recovery feature.", "error", "Account not requested", "index.php", "[cancel]");
		}

		// Inser the new user into the database!

		$CODE = rand(111111111111, 9999999999999);
		$DB->query("insert into users (username, password, email, " .
		"addedby, emailcode, emailValid) " .
		"values (?, ?, ?, ?, ?, ?)", array (
			stripcslashes($NEW_USER
		), "$PASSWORD_ENC", "$NEW_EMAIL", $MySelf->getID(), "$CODE", !getConfig("emailValidation")));

		// Were we successful?
		if ($DB->affectedRows() == 0) {
			// No!
			makeNotice("Could not create user!", "error");
		} else if(getConfig("emailValidation") == 1) {
			// Load more globals
			global $SITENAME;
			global $URL;
			global $VERSION;

			// Assemble the activation url.
			$ACTIVATE = $URL . "/index.php?action=activate&code=$CODE";

			// Send a confirmation email
			$EMAIL = getTemplate("accountrequest", "email");
			$EMAIL = str_replace("{{IP}}", "$_SERVER[REMOTE_ADDR]", $EMAIL);
			$EMAIL = str_replace("{{URL}}", "$URL", $EMAIL);
			$EMAIL = str_replace("{{DATE}}", date("r", $TIMEMARK), $EMAIL);
			$EMAIL = str_replace("{{ACTIVATE}}", "$ACTIVATE", $EMAIL);
			$EMAIL = str_replace("{{CORP}}", "$SITENAME", $EMAIL);
			$to = $NEW_EMAIL;
			$DOMAIN = $_SERVER['HTTP_HOST'];
			$headers = "From:" . $MB_EMAIL;
			mail($to,$VERSION,$EMAIL,$headers);
			makeNotice("A confirmation email has been sent to your supplied email address.<br>Please follow the instructions therein.", "notice", "Account created");
		}else{
			makeNotice("Your account must be approved by HR<br/>Username: " . stripcslashes($NEW_USER), "notice", "Account created");
		}
	}
}
?>

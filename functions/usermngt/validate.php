<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/usermngt/validate.php,v 1.2 2008/01/06 19:41:51 mining Exp $
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

function validate() {

	global $MySelf;
	global $MB_EMAIL;

	// Are we already validated?
	if ($MySelf->getEmailvalid()) {
		makeNotice("You are already validated.");
	}

	// Is it what the user wants?
	confirm("Do you wish to be sent a confirmation eMail now?");

	// We need some variables.
	global $DB;
	global $MySelf;
	global $TIMEMARK;
	$CODE = rand(111111111111, 9999999999999);

	// Update the user.
	$DB->query("UPDATE users SET emailcode ='$CODE' WHERE username='" . $MySelf->getUsername() . "' LIMIT 1");

	if ($DB->affectedRows() == 1) {

		$email = $DB->getCol("SELECT email FROM users WHERE username = '" . $MySelf->getUsername() . "' AND deleted='0' LIMIT 1");

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
		$to = $email[0];
		$DOMAIN = $_SERVER['HTTP_HOST'];
		
		$headers = "From:" . $MB_EMAIL;
		mail($to,$VERSION,$EMAIL,$headers);
		makeNotice("A confirmation email has been sent to your supplied email address.<br>Please follow the instructions therein.", "notice", "Account created");

	} else {

		makeNotice("Could not send out the confirmation eMail!", "error");

	}
}
?>
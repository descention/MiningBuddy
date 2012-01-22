<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/system/mailUser.php,v 1.2 2008/01/06 19:41:51 mining Exp $
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



function mailUser($mail, $subject) {

	// We need the Database to gather all the eMails.
	global $DB;
	global $MB_EMAIL;

	// We need something to mail around!
	if ((empty ($mail)) || (empty ($subject))) {
		makeNotice("Nothing to send in mailUser()!", "error", "Internal Error");
	}

	// Get the eMail addresses. Only use emails that are opt-in and valid.
	global $IS_DEMO;
	if (!$IS_DEMO) {
		$EMAIL_DS = $DB->query("SELECT username, email FROM users WHERE optIn='1' AND emailValid='1' AND deleted='0'");
	
	// Do this for everyone that opt-ed in.
	while ($recipient = $EMAIL_DS->fetchRow()) {
		$copy = str_replace("{{USER}}", "$recipient[username]", $mail);
		$to = $recipient[email];
		$DOMAIN = $_SERVER['HTTP_HOST'];
		$from = "MiningBuddy@" . $DOMAIN;
		$headers = "From:" . $MB_EMAIL;
		mail($to,$subject,$copy,$headers);
	}
	}

}
?>
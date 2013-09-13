<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/usermngt/lostPassword.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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

function lostPassword($user = "", $reason = "lost") {
	// load the globals.
	global $DB;
	global $VERSION;
	global $SITENAME;
	global $MB_EMAIL;

	if (empty ($user)) {
		// Has the user entered both username and email in the form?
		if (("$_POST[username]" == "") || ("$_POST[email]" == "")) {
			// no!
			makeNotice("You need to enter both an username and eMail!", "error");
		}

		// Sanitize
		$POST_USERNAME = sanitize("$_POST[username]");
		$POST_EMAIL = sanitize("$_POST[email]");

	} else {

		// Look up the email address for the user.
		$POST_USERNAME = strtolower(sanitize("$user"));
		$results = $DB->getAssoc("select username, email from users where username='$POST_USERNAME' AND deleted='0'  limit 1");
		$POST_EMAIL = $results[$user];
	}

	// Fetch los resultos! Ole! 
	$results = $DB->query("select * from users where username='$POST_USERNAME' and email='$POST_EMAIL' AND deleted='0'  limit 1");

	// Have we hit something?
	if ($results->numRows() == "0") {
		// No! No such user!
		makeNotice("No such record or username and/or eMail wrong!", "error");
	}

	// Create random new pass and salt it.
	$newpass = base64_encode(rand(1111111111, 9999999999));
	$newpass_crypt = encryptPassword($newpass);

	// Fill the template.
	while ($row = $results->fetchRow()) {

		if ("$row[confirmed]" == 0) {
			makeNotice("Your account has not yet been confirmed by your CEO yet!", "error");
		}

		$email = getTemplate("lostpass", "email");
		$email = str_replace("{{USERNAME}}", $row['username'], $email);
		$email = str_replace("{{IP}}", $_SERVER['REMOTE_ADDR'], $email);
		$email = str_replace("{{VERSION}}", $VERSION, $email);
		$email = str_replace("{{SITENAME}}", $SITENAME, $email);
		$email = str_replace("{{NEWPASS}}", $newpass, $email);

		// Remember the email. We dont want to use the supplied one.
		$to = $row['email'];
	}

	// Set the new password into the database.
	$DB->query("update users set password = '$newpass_crypt' where username='$POST_USERNAME' and email='$POST_EMAIL'");

	// mail it.
		$DOMAIN = $_SERVER['HTTP_HOST'];
		$headers = "From:" . $MB_EMAIL;
	if ("$to" == "") {
		makeNotice("Internal Error: No valid email found in lostPassword!", "error");
	} else {
		global $MAIL;
		if(isset($MAIL)){
			$MAIL->AddAddress($to);
                        $MAIL->Subject = $VERSION;
                        $MAIL->Body = $email;
			$MAIL->Send();
		}else{
			mail($to,$VERSION,$email,$headers);
		}
	}

	// print success page.
	if (empty ($user)) {
		makeNotice("A new password has been mailed to you.", "notice", "Password sent");
	}

}
?>
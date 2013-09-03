<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/usermngt/changeEmail.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * Change eMail.
 */
function changeEmail() {

	global $SALT;
	global $DB;
	global $MySelf;

	// Are we allowed to change our email?
	if (!$MySelf->canChangeEmail()) {
		makeNotice("You are not allowed to change your email. Ask your CEO to re-enable this feature for your account.", "error", "Forbidden");
	}

	/*
	* At this point we know that the user who submited the
	* email change form is both legit and the form was not tampered
	* with. Proceed with the email-change.
	*/

	// its easier on the eyes.
	$email = sanitize($_POST[email]);
	$username = $MySelf->getUsername();

	// Update the Database. 
	global $IS_DEMO;
	if (!$IS_DEMO) {
		$DB->query("update users set email = '$email', emailvalid = '0' where username = '$username'");
		makeNotice("Your email information has been updated. Thank you for keeping your records straight!", "notice", "Information updated");
	} else {
		makeNotice("Your email would have been changed. (Operation canceled due to demo site restrictions.)", "notice", "Email change confirmed");
	}

}
?>
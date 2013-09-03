<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/usermngt/changePassword.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * Change Password.
 */
function changePassword() {
	global $DB;
	global $MySelf;

	// sanitizing.
	$username = sanitize($MySelf->getUsername());

	// Are we allowed to change our password?
	if (!$MySelf->canChangePwd()) {
		makeNotice("You are not allowed to change your password. Ask your CEO to re-enable this feature for your account.", "error", "Forbidden");
	}

	// Passwords the very same?
	if ("$_POST[password1]" != "$_POST[password2]") {
		makeNotice("Your entered passwords do not match, please head back, and try again!", "error", "Password not changed", "index.php?action=changepw", "[retry]");
	}
	
	// Passwords empty?
	if (empty($_POST[password1]) || empty($_POST[password2])) {
		makeNotice("You need to enter passwords in both fields!!", "error", "Password missing!", "index.php?action=changepw", "[retry]");
	}

	/*
	* At this point we know that the user who submited the
	* password change form is both legit and the form was not tampered
	* with. Proceed with the password-change.
	*/

	// encode both supplied passwords with crypt.
	$password = encryptPassword("$_POST[password1]");
	$oldpasswd = encryptPassword("$_POST[password]");

	// Update the Database. 
	global $IS_DEMO;
	if (!$IS_DEMO) {
		$DB->query("update users set password = '$password' where username = '$username' and password ='$oldpasswd'");
		if ($DB->affectedRows() == 1) {
			makeNotice("Your password has been changed.", "notice", "Password change confirmed");
		} else {
			makeNotice("Your password could not have been changed! Database error!", "error", "Password change failed");
		}
	} else {
		makeNotice("Your password would have been changed. (Operation canceled due to demo site restrictions.)", "notice", "Password change confirmed");
	}

}
?>
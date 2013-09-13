<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/preAuth.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * This file handles all pre-auth stuff. This will re rewritten soon.
 */

/* Logout
 * Maybe the user wants to logout. Lets grant him that wish.
 */
if (isset($_GET['auth']) && $_GET['auth'] == "logout") {
	// Destroy all login related information.

	// Are we sure?
	confirm("Do you wish to logout now?");

	// Sanitize the input and delete all relevant tokens from the database.
	$TOKEN = sanitize($_SESSION['auth']);
	$DB->query("UPDATE auth SET disabled='1' WHERE authkey = '$TOKEN'");

	// Destroy the cookie jar.
	$_SESSION['lastModDisplay'] = false;
	session_destroy();

	// .. then print it.
	makeNotice("You are now logged out.");
	die();
}

/*
 * Someone lost their password.
 */
if (isset($_GET['auth']) && $_GET['auth'] == "lostpass") {
	
	//	global $page;
	//	$page = makeLostPassForm().makeFooter();
	//	print ($page);
	//	die();
	$html = new html;
	//	$html->execFlush(makeLostPassForm);
	$html->addBody(makeLostPassForm());
	
	die();
}

/*
 * Someone wants a new account.
 */
if (isset($_GET['auth']) && $_GET['auth'] == "requestaccount") {
	
	global $page;
	$page = makeRequestAccountPage() . makeFooter();
	print ($page);
	
	die();
}

/*
 * Someone wants a new account, and has submited the form.
 */
if (isset($_POST['action']) && $_POST['action'] == "requestaccount") {
	requestAccount();
	die();
}

/*
 *  Someone lost their password and has submited the form.
 */
if (isset($_POST['action']) && $_POST['action'] == "lostpass") {
	lostPassword();
	die();
}

/*
 * Someone wants to validate their email address.
 */
if (isset($_GET['action']) && $_GET['action'] == "activate") {
	if (empty ($_GET['code'])) {
		makeNotice("You need to supply an activation code!", "error", "Not confirmed");
	} else {
		$DB->query("UPDATE users SET emailvalid = '1' where emailcode='$_GET[code]' LIMIT 1");
		$DB->query("UPDATE users SET emailcode = '0' where emailcode='$_GET[code]' LIMIT 1");
		if ($DB->affectedRows() != 1) {
			makeNotice("Error while verfifying your email address!<br>Please ask your CEO for assistance.", "error");
		} else {
			makeNotice("Your email has been verified. Thank you!", "notice", "Account verified");
		}
	}
}
?>
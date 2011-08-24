<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/authVerify.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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

/* authVerify(user pass);
 * This will check the username and password against the database.
 * Will return true on success and false on fail.
 *
 * Note: The password needs to be salted.
 */

function authVerify($username, $password, $trust = false) {

	global $DB;
	global $TIMEMARK;

	// lower case it.
	$username = strtolower($username);

	// and query it.
	if (!$password && $trust) {
		// Passwordless login (WAHHHHH!!!!)
		$userDS = $DB->query("select * from users where username='$username' AND deleted='0' limit 1");
		$passwordless = true;
	} else {
		// Sane login.
		$userDS = $DB->query("select * from users where username='$username' and password='$password' AND deleted='0' limit 1");
		$passwordless = false;
	}

	// No one found
	if ($userDS->numRows() == 0) {

		// Log failed attempts.
		$user_valid = $DB->getCol("SELECT COUNT(username) FROM users WHERE username = '$username' LIMIT 1");
		$user_valid = $user_valid[0];
		$DB->query("INSERT INTO failed_logins (time, ip, username, username_valid, agent) VALUES (?,?,?,?,?)", array (
			$TIMEMARK,
			"$_SERVER[REMOTE_ADDR]",
			stripslashes(sanitize($username
		)), $user_valid, sanitize($_SERVER[HTTP_USER_AGENT])));

		return (false);
	} else {
		$user = $userDS->fetchRow();
	}

	// Is the account activated yet?
	if (("$user[canLogin]" != "1") || ("$user[confirmed]" != "1")) {
		// Nyet!
		makeNotice("Your account has not yet been activated or been blocked." . "<br>Please ask your CEO for assistance.", "error", "Unable to login");
	} else {

		/* HOLD IT RIGHT THERE!
		 * We have a login from IGB with valid trust setting. BUT HEY!
		 * Does the API key match?
		 */
		if ($passwordless) {
			// Load the api!
			$api = new api($user[id]);
			if (!$api->valid()) {
				// NO valid api key!!!!11
				session_destroy();
				makenotice("For fast login you need to supply your API key. Log in to MiningBuddy out of game and set your API key under preferences. Only then can you do fast logins. <a href=\"http://myeve.eve-online.com/api/default.asp?\">Visit the EVE api page here (right click, copy URL)</a>", "warning", "ACCESS DENIED");
				die();
				// return (false);
			} else {
				$MyAccount = new user($user, $TIMEMARK);
				return ($MyAccount);
			}
		} else {
//			// Out of game logins.
			$MyAccount = new user($user, $TIMEMARK);
			return ($MyAccount);
		}
	}
	// We dont :(
	return false;
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/createAuthKey.php,v 1.2 2008/01/03 15:49:08 mining Exp $
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
 * This function creates a temporary authorization key, and stores it in
 * the database (auth). It also stores originating IP, Browser Type and
 * when it was issued. This prevents cookie theft.
 */

function createAuthKey($MySelf) {

	// Create the globals.
	global $DB;
	global $TIMEMARK;
	
	// Set up other variables needed for auth.
	$KEY = md5(uniqid(rand(), TRUE));
	$USER = $MySelf->getID();
	$IP = $_SERVER[REMOTE_ADDR];
	$AGENT = sanitize($_SERVER[HTTP_USER_AGENT]);

	// Remove any other current auth keys in the database.
	$DB->query("UPDATE auth SET disabled='1' WHERE user = '$USER'");

	// Insert the Auth token into the auth database.
	$DB->query("INSERT INTO auth (authkey, user, issued, ip, disabled, agent) 
				                VALUES (?,?,?,?,?,?)", array (
		"$KEY",
		"$USER",
		"$TIMEMARK",
		"$IP",
		0,
		"$AGENT"
	));
	
	// Set the key.
	$_SESSION[auth] = $KEY;

}
?>
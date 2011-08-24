<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/authKeyIsValid.php,v 1.2 2008/01/03 15:49:08 mining Exp $
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
 * Checks if the current user has a valid auth token.
 */

function authKeyIsValid() {
	// Globals.
	global $DB;
	global $TIMEMARK;
	global $MySelf;

	$MySelf = unserialize(base64_decode($_SESSION[MySelf]));

	// No object, no service.
	if (!is_object($MySelf)) {
		return false;
	}

	// Set up other variables needed for auth.
	$TOKEN = sanitize($_SESSION[auth]);
	$IP = $_SERVER[REMOTE_ADDR];
	$AGENT = sanitize($_SERVER[HTTP_USER_AGENT]);
	$USER = $MySelf->getID();

	// Ask the oracle.
	$tokens = $DB->query("SELECT * FROM auth WHERE authkey = '$TOKEN' AND disabled ='0' ORDER BY issued DESC limit 1");

	
	// No rows, no auth.
	if ($tokens->numRows() == 0) {
		return false;
	}

	$token = $tokens->fetchRow();
	$TTL = $token[issued] + (getConfig("TTL") * 60);
	
	// Check that every bit of the auth token matches valid values.
	if (("$token[authkey]" == "$TOKEN") && ("$token[user]" == "$USER") && ("$token[ip]" == "$IP") && (substr($token[agent], 0, 100) == substr($AGENT, 0, 100)) && ($TIMEMARK <= $TTL)) {
		// It does. Token valid.
		return $MySelf;
	} else {
		// Something is not right. Destroy all login tokens.
		$DB->query("DELETE FROM auth WHERE authkey ='$TOKEN'");
		return false;
	}

}
?>
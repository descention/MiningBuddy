<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/toggleLogin.php,v 1.2 2008/05/03 11:45:45 mining Exp $
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
  * This is a quick way to toggle someones login capability.
  */
 
 function toggleLogin () {
 	
 	global $DB;
 	global $MySelf;
 	global $IS_DEMO;
 	
 	if ($IS_DEMO) {
		makeNotice("The user would have been changed. (Operation canceled due to demo site restrictions.)", "notice", "Password change confirmed");
	}

	// Are we allowed to Manage Users?
	if (!$MySelf->canManageUser()) {
		makeNotice("You are not allowed to edit Users!", "error", "forbidden");
	}

	if ($MySelf->getID() == $_GET[id]) {
		makeNotice("You are not allowed to block yourself!", "error", "forbidden");
	}
	
	// Wash ID.
	numericCheck($_GET[id]);
	$ID = sanitize($_GET[id]);
	
	// update login capability.
	$DB->query("UPDATE users SET canLogin=1 XOR canLogin WHERE id='" . $ID . "' LIMIT 1");
 	
 	$username = idToUsername("$ID");
 	$p = substr($username, 0, 1);
 	
 	// Return.
 	header("Location: index.php?action=editusers&l=$p");
 	
 }
 
 ?>
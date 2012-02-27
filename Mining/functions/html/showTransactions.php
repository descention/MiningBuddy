<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showTransactions.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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
 
 function showTransactions() {
 	
 	// Global Fun!
 	global $DB;
 	global $MySelf;
 	
 	// Are we allowed to do this?
	if (!$MySelf->isAccountant()) {
		makeNotice("You are not an accountant to your corporation. Access denied.", "error", "Access denied");
	}
 	
 	// Sanity check.
 	numericCheck($_GET[id],0);
 	$username = idToUsername($_GET[id]);
 	$id = $_GET[id];
 	
 	// Load the transaction log.
 	$page = "<h2>Transaction log for " . ucfirst($username) . "</h2>";
 	$trans = getTransactions($id);
 	
 	if (!$trans) {
 		$page .= "<b>There are no transactions for $username.</b>";
 	} else {
 		$page .= $trans;
 	}
 	
 	// Add the backlink.
 	$page .= "<br><br><a href=\"index.php?action=payout\">Back to Payouts</a>";
 
 	// Return the page.	
 	return ($page);
 	
 }
 
 ?>
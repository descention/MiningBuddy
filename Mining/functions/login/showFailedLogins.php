<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/showFailedLogins.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
  * This show a list of all failed login for one or all users.
  */
 
 function showFailedLogins ($limit, $user = false) {
 	
 	global $DB;
 	$user = sanitize(stripslashes($user));
 	 	
 	// Specify a user, if given.
 	if ($user){
 		$addQuery = "WHERE username = '" . $user ."'";
 	}
 	
 	// Set the default results (10)
 	if ($limit < 1) {
 		$limit = 10;
 	} 
 
 	// Ask the oracle.
 	$FailedDB = $DB->query("SELECT * FROM failed_logins $addQuery LIMIT $limit");
 	
 	// Check for results.
 	if ($FailedDB->numRows() > 0) {

		// We have failed logins. 		
 		$table = new table(5, true);
 		
 		// Add a table header accordingly.
 		if ($user) {
 			$table->addHeader("Failed logins for user ".ucfirst(stripslashes($user)).".");
 		} else {
 			$table->addHeader("Failed logins");
 		}
 		
 		// Add Table Description
 		$table->addRow();
 		$table->addCol("Incident");
 		$table->addCol("Occurance");
 		$table->addCol("IP");
 		$table->addCol("Username");
 		$table->addCol("Valid Username");
// 		$table->addCol("Agent");
 		
 		// Add the data-rows.
 		while ($log = $FailedDB->fetchRow()) {
 			$table->addRow();
 			$table->addCol(str_pad($log[incident], 4, "0", STR_PAD_LEFT));
 			$table->addCol(date("d.m.y h:m:s", $log[time]));
 			$table->addCol($log[ip]);
 			
 			if ($log[username_valid]) {
				$userID = usernameToID(stripslashes(sanitize($log[username])));
				$link = "<a href=\"index.php?action=edituser&id=$userID\">".ucfirst(stripslashes(sanitize($log[username])))."</a>";
				$table->addCol($link); 				
 			} else {
 				$table->addCol(ucfirst(sanitize($log[username])));
 			}
 			$table->addCol(yesno($log[username_valid]));
// 			$table->addCol($log[agent]);
 		}
 		
 		$table->addHeaderCentered("Securing your system is your responsibility!");
 		
 		return ("<br>" . $table->flush());
 		
 	} else {
 		// No failed logins.
 		return (false);
 	}
 }
 
 ?>
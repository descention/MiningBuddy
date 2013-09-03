<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/editRanks.php,v 1.3 2008/01/02 20:01:32 mining Exp $
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
  * Well, this.. edits ranks, really.
  */
 
 function editRanks() {
 	
 	// Doh, globals!
 	global $MySelf;
 	global $DB;
 	
 	// Are we allowed to do this?
 	if (!$MySelf->canEditRank()) {
		makeNotice("You do not have sufficient rights to access this page.", "warning", "Access denied"); 
 	}

	// Get all unique rank IDS. 	
 	$ranks = $DB->query("SELECT DISTINCT rankid FROM ranks");

	// Edit each one at a time.
	while ($rankID = $ranks->fetchRow()) {
		$ID = $rankID[rankid];
		if (isset($_POST["title_".$ID."_name"])) {
 			// Cleanup
 			$name = sanitize($_POST["title_".$ID."_name"]);
 			numericCheck($_POST["order_".$ID], 0);
 			$order = $_POST["order_".$ID];
 			
 			// Update the Database.
 			$DB->query("UPDATE ranks SET name='".$name."', rankOrder='".$order."' WHERE rankid='".$ID."' LIMIT 1");
 		}
	} 	
	
	header("Location: index.php?action=showranks");
 }
 
 ?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/delRank.php,v 1.2 2008/01/02 20:01:32 mining Exp $
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
  * Well, this.. adds a rank, really.
  */
 
 function delRank() {
 	
 	// Doh, globals!
 	global $MySelf;
 	global $DB;
 	
 	// Are we allowed to do this?
 	if (!$MySelf->canEditRank()) {
		makeNotice("You do not have sufficient rights to access this page.", "warning", "Access denied"); 
 	}
 	
 	// Verify it.
 	numericCheck($_GET[id], 0); 	
 	
 	// Confirm it.
 	confirm("Do you really want to permanently delete rank #". str_pad($_GET[id], 3, "0", STR_LEFT_PAD) ."?");
 	
 	// Insert Rank into Database
 	$DB->query("DELETE FROM ranks WHERE rankid='".$_GET[id]."' LIMIT 1");
 	
 	// Check for success
 	if ($DB->affectedRows() == 1) {
 		header("Location: index.php?action=showranks");
 	} else {
 		makeNotice("Unable to add the rank into the database!", "warning", "Database Error!");
 	}
 }
 
 ?>
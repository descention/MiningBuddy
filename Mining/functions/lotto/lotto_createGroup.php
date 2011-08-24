<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_createGroup.php,v 1.1 2008/09/08 09:04:17 mining Exp $
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
  * This file creates and handles all calls for creation of Lotto groups.
  */
 
 function lotto_createGroup () {
 	
 	// Check that we are allowed to do this.
 	global $MySelf;
 	if ($MySelf->lotto_isAdmin() != true) {
 		makeNotice("Not authorized", "error", "You are not allowed to execute this command.");
 	}
 	
 	// Post check to handle submited form.
 	if (isset($_POST[check])) {
 		die("Would create group now...");
 	}
 	
	// Create the table
	$table = new table(2, true);
	$table->addHeader("Create new Group");
	
	$table->addRow();
	$table->addCol("Group ID:");
	$table->addCol("NDY");
	
	$table->addRow();
	$table->addCol("Group name:");
	$table->addCol("<input type=\"text\" name=\"name\" size=\"30\" maxlength=\"30\">");
	
	$table->addRow();
	$table->addCol("Has jackpot:");
	$table->addCol("<input type=\"checkbox\" name=\"jackpot\" checked>");
	
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Create Group\">");

	// Form goodness
	$form = "<form method=\"POST\" action=\"index.php\">";
	$form .= "<input type=\"hidden\" name=\"action\" value=\"lotto_createGroup\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"check\">";

	// Return the HTML creation form.
 	return ("<h2>Lotto management :: Create new Group</h2>" . $form . $table->flush() . "</form>");
 	
 }
 
 ?>
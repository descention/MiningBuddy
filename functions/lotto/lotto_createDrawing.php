<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_createDrawing.php,v 1.8 2007/02/02 17:32:42 mining Exp $
 *
 * Copyright (c) 2005, 2006, 2007 Christian Reiss.
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

function lotto_createDrawing() {

	// The usual susglobals. ;)
	global $DB;
	global $MySelf;
	global $TIMEMARK;
	$count = $_POST[count];

	// is Lotto enabled at all?
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Deny access to non-lotto-officials.
	if (!$MySelf->isLottoOfficial()) {
		makeNotice("You are not allowed to do this!", "error", "Permission denied");
	}

	// We only allow boards greater 1 ticket. 	
	if (!is_numeric($count) && $count < 1) {
		makeNotice("Invalid count for the new drawing!", "error", "Invaid Count", "index.php?action=editLotto", "[Cancel]");
	}

	// Is there already a drawing opened?
	if (lotto_getOpenDrawing()) {
		makeNotice("You can only have one drawing open at the same time!", "error", "Close other drawing", "index.php?action=editLotto", "[Cancel]");
	}

	$DB->query("INSERT INTO lotto (opened,isOpen) VALUES (?,?)", array (
		$TIMEMARK,
		"1"
	));

	if ($DB->affectedRows() != 1) {
		makeNotice("Error creating new drawing in database! Inform admin!", "error", "Internal Error", "index.php?action=editLotto", "[Cancel]");
	}

	// Which ID are we now?
	$drawing = lotto_getOpenDrawing();

	// insert tickets!
	for ($i = 1; $i <= $_POST[count]; $i++) {
		$DB->query("INSERT INTO lotteryTickets (ticket, drawing) VALUES ('$i', '$drawing')");
	}

	makeNotice("Drawing created, have fun!", "notice", "Here you go.", "index.php?action=lotto", "lotto! LOTTO!");
}
?>
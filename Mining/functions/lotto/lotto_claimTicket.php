<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_claimTicket.php,v 1.9 2007/02/02 17:32:42 mining Exp $
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

function lotto_claimTicket() {

	global $DB;
	global $MySelf;
	$LOTTO_MAX_PERCENT = getConfig("lottoPercent");
	
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Only people with parents consent may play!
	if (!$MySelf->canPlayLotto()) {
		makeNotice("Im sorry, but you are not allowed to play Lotto. " .
		"Ask your CEO or a friendly Director to enable this for you.", "warning", "Unable to play :(");
	}

	// Ticket ID sane?
	numericCheck($_GET[ticket], 0);
	$ticket = $_GET[ticket];

	// Get the drawing ID.
	$drawing = lotto_getOpenDrawing();

	// Get my credits
	$MyStuff = $DB->getRow("SELECT lottoCredit, lottoCreditsSpent FROM users WHERE id='" . $MySelf->getID() . "'");
	$Credits = $MyStuff[lottoCredit];
	$CreditsSpent = $MyStuff[lottoCreditsSpent];

	// Are we broke?
	if ($Credits < 1) {
		makeNotice("You can not afford the ticket, go get more credits!", "warning", "You're broke!'", "index.php?action=lotto", "[ashamed]");
	}
	
	// Now check if we bust it.
	$myTickets = lotto_checkRatio($drawing);
	if ($myTickets <= 0) {
		makeNotice("You are already owning the maximum allowed tickets!", "warning", "Exceeded ticket ratio!",
		"index.php?action=lotto", "[Cancel]");
	}

	// Deduct credit from account.
	$newcount = $Credits -1;
	$DB->query("UPDATE users SET lottoCredit='$newcount' WHERE id='" . $MySelf->getID() . "' LIMIT 1");
	if ($DB->affectedRows() != 1) {
		makeNotice("Internal Error: Problem with your bank account... :(", "error", "Internal Error", "index.php?action=lotto", "[Cancel]");
	}

	// Add to "Spent".
	$spent = $CreditsSpent +1;
	$DB->query("UPDATE users SET lottoCreditsSpent='$spent' WHERE id='" . $MySelf->getID() . "' LIMIT 1");
	if ($DB->affectedRows() != 1) {
		makeNotice("Internal Error: Problem with your bank account... :(", "error", "Internal Error", "index.php?action=lotto", "[Cancel]");
	}

	// Lets check that the ticket is still unclaimed.
	$Ticket = $DB->getCol("SELECT owner FROM lotteryTickets WHERE ticket='$ticket' AND drawing='$drawing'");
	if ($Ticket[0] >= 0) {
		makeNotice("Im sorry, but someone else was faster that you and already claimed that ticket.", "warning", "Its gone, Jim!", "index.php?action=lotto", "[Damn!]");
	}

	// Give him the ticket.
	$DB->query("UPDATE lotteryTickets SET owner='" . $MySelf->getID() . "' WHERE ticket='$ticket' AND drawing='$drawing' LIMIT 1");

	if ($DB->affectedRows() == 1) {
		Header("Location: index.php?action=lotto");
	} else {
		makeNotice("Internal Error: Could not grant you the ticket :(", "error", "Internal Error", "index.php?action=lotto", "[Cancel]");
	}
}
?>
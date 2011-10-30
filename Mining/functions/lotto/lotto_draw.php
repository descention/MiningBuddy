<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_draw.php,v 1.9 2007/02/02 17:32:42 mining Exp $
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

function lotto_draw() {

	// We need some globals
	global $MySelf;
	global $DB;
	global $TIMEMARK;

	// is Lotto enabled at all?
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Deny access to non-lotto-officials.
	if (!$MySelf->isLottoOfficial()) {
		makeNotice("You are not allowed to do this!", "error", "Permission denied");
	}

	// Database
	$max = lotto_getOpenDrawing();

	// confirm!
	confirm("Do you want to draw the winner for Drawing #$max now?");

	// No drawing open!
	if (!$max) {
		makeNotice("There is no open lottery. Open a new one, and try again.", "warning", "No open drawing", "index.php?action=editLotto", "[cancel]");
	}

	// Lock remaining tickets.
	$DB->query("UPDATE lotteryTickets SET owner='-2' WHERE drawing='$max' AND owner<'0'");

	// Pick the winner.
	$NrTickets = $DB->getCol("SELECT MAX(ticket) AS max FROM lotteryTickets WHERE drawing='$max'");
	$Winner = rand(1, $NrTickets[0]);

	// Set the ticket as "winner":
	$DB->query("UPDATE lotteryTickets SET isWinner='1' WHERE ticket='$Winner' AND drawing='$max'");

	// Get ID of possible winner:
	$luckyOne = $DB->getCol("SELECT owner FROM lotteryTickets WHERE isWinner='1' AND drawing='$max' LIMIT 1");
	$luckyOne = $luckyOne[0];

	// Calculate the potsize.
	$potSize = $DB->getCol("SELECT COUNT(id) AS count FROM lotteryTickets WHERE owner>='0' AND drawing='$max'");
	$potSize = $potSize[0] * 1000000;
	$potSizeOld = $potSize;

	// Get the JackPot.
	$jackPot = $DB->getCol("SELECT value FROM config WHERE name='jackpot' LIMIT 1");
	$jackPot = $jackPot[0];
	$potSize = $potSize + $jackPot;

	if ($luckyOne >= 0) {
		// We have a winner!
		$DB->query("UPDATE lotto SET winner='$luckyOne' WHERE drawing='$max' LIMIT 1");

		// Give him the money.
		$transaction = new transaction($luckyOne, 0, $potSize);
		$transaction->setReason("won the lottery");
		$transaction->commit();

		// Clean up the jackpot.
		$DB->query("DELETE FROM config WHERE name='jackpot' LIMIT 1");

	} else {

		// No winner, unclaimed ticket won :(
		$DB->query("UPDATE lotto SET winner='-1' WHERE drawing='$max' LIMIT 1");

		// Add to jackpot.
		$DB->query("DELETE FROM config WHERE name='jackpot' LIMIT 1");
		$DB->query("INSERT INTO config (name, value) VALUES ('jackpot','$potSize')");
	}

	$DB->query("UPDATE lotto SET closed='$TIMEMARK' WHERE drawing='$max' LIMIT 1");
	$DB->query("UPDATE lotto SET isOpen='0' WHERE drawing='$max' LIMIT 1");
	$DB->query("UPDATE lotto SET winningTicket='$Winner' WHERE drawing='$max' LIMIT 1");
	$DB->query("UPDATE lotto SET potSize='$potSizeOld' WHERE drawing='$max' LIMIT 1");

	header("Location: index.php?action=lotto");
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_playLotto.php,v 1.9 2007/02/02 17:32:42 mining Exp $
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

function lotto_playLotto() {

	// Globals, as usual.
	global $DB;
	global $MySelf;
	$LOTTO_MAX_PERCENT = getConfig("lottoPercent");
	$ID = $MySelf->getID();

	// is Lotto enabled at all?
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Only people with parents consent may play!
	if (!$MySelf->canPlayLotto()) {
		makeNotice("Im sorry, but you are not allowed to play Lotto. " .
		"Ask your CEO or a friendly Director to enable this for you.", "warning", "Unable to play :(");
	}

	// Get my credits
	$MyStuff = $DB->getRow("SELECT lottoCredit, lottoCreditsSpent FROM users WHERE id='" . $MySelf->getID() . "'");
	$Credits = $MyStuff[lottoCredit];
	$CreditsSpent = $MyStuff[lottoCreditsSpent];

	// Handle empty accounts.
	if ($Credits < 1) {
		$Credits = "None";
	}

	if ($CreditsSpent < 1) {
		$CreditsSpent = "None";
	}

	// My Info Table.
	$MyInfo = new table(2, true);
	$MyInfo->addHeader(">> My lotto assets");
	$MyInfo->addRow();
	$MyInfo->addCol("My balance (tickets):");
	$MyInfo->addCol($Credits);
	$MyInfo->addRow();
	$MyInfo->addCol("Total spent (tickets):");
	$MyInfo->addCol($CreditsSpent);
	$MyInfo->addHeader("Need more credits? <a href=\"index.php?action=buycredits\">Buy them here!</a>");

	// Print resent pots.
	$MyWins = new table(4, true);
	$MyWins->addHeader(">> Recent jackpots");

	$MyWins->addRow("#060622");
	$MyWins->addCol("Drawing");
	$MyWins->addCol("Winner");
	$MyWins->addCol("Winning Ticket");
	$MyWins->addCol("Jackpot");

	$Jackpots = $DB->query("SELECT * FROM lotto WHERE isOpen='0'");

	if ($Jackpots->numRows() >= 1) {
		while ($jp = $Jackpots->fetchRow()) {
			//			$TotalTickets_DS = $DB->Query("SELECT ticket FROM lotteryTickets WHERE drawing='" . $woot[drawing] . "' AND owner >= '0'");
			//			$TotalTickets = $TotalTickets_DS->numRows();

			$MyWins->addRow();

			$MyWins->addCol("<a href=\"index.php?action=lotto&showdrawing=" . $jp[drawing] . "\">#" . str_pad($jp[drawing], 3, "0", STR_PAD_LEFT) . "</a>");
			if ($jp[winner] == "-1") {
				$MyWins->addCol("<i>No one</i>");
			} else {
				$MyWins->addCol(ucfirst(idToUsername($jp[winner])));
			}

			$MyWins->addCol("#" . str_pad($jp[winningTicket], 3, "0", STR_PAD_LEFT));
			$MyWins->addCol(number_format($jp[potSize]) . " ISK");

			$GotWinners = true;
		}
	}

	// jackpot! WOOT!
	$Jackpot = $DB->getCol("SELECT value FROM config WHERE name='jackpot' LIMIT 1");
	$MyWins->addHeader("The current jackpot is at " . number_format($Jackpot[0], 2) . " ISK.");

	//	$MyWins->addHeader("Please contact your lotto officer to claim your prize.");

	// Load the current drawing.
	if (!$_GET[showdrawing]) {
		$drawingID = lotto_getOpenDrawing();
		$drawingID = $drawingID[0];
	} else {
		numericCheck($_GET[showdrawing], 0);
		$drawingID = $_GET[showdrawing];
	}

	// Only do this if we have an open  drawing, doh!
	if ($drawingID) {

		$TICKETS = $DB->query("SELECT *  FROM lotteryTickets WHERE drawing = '$drawingID' ORDER BY ticket");
		$allowedTickets = lotto_checkRatio($drawingID);

		// Table header
		$drawing = new table(2, true);
		$drawing->addHeader(">> Drawing #$drawingID");

		// 1=left side, 0=right side.
		$side = 1;
		while ($ticket = $TICKETS->fetchRow()) {
			$ticketCount++;
			// If we are on the left side, open up a new table row.
			if ($side == 1) {
				$drawing->addRow();
			}

			// Ticket owned already?
			if ($ticket[owner] == -1) {
				if ($Credits >= 1 && $allowedTickets > 0) {
					$drawing->addCol("<a href=\"index.php?action=claimTicket&drawing=$max&ticket=" . $ticket[ticket] . "\">#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . "</a> (unclaimed)");
				} else {
					$drawing->addCol("#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . " (unclaimed)");
				}
			}
			elseif ($ticket[owner] >= 0) {
				// Increase the chances counter.
				if ($ticket[owner] == $ID) {
					$chances++;
				}
				if ($ticket[isWinner]) {
					$drawing->addCol("#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . " (" . idToUsername($ticket[owner]) . ") <font color=\"#00ff00\"><b>WINNER!</b></font>");
				} else {
					$drawing->addCol("#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . " (" . idToUsername($ticket[owner]) . ")");
				}
			} else {
				if ($ticket[isWinner]) {
					$drawing->addCol("#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . " (locked) <font color=\"#00ff00\"><b>WINNER!</b></font>");
				} else {
					$drawing->addCol("#" . str_pad($ticket[ticket], 4, "0", STR_PAD_LEFT) . " (locked)");
				}
			}

			// Toggle sides.
			$side = 1 - $side;
			$AreTickets = true;
		}

		// My Chances
		$winningChance = number_format(100 / ($ticketCount / $chances), 3) . "%";

		// Even the odds ;)
		if ($side == 0) {
			$drawing->addCol("---");
		}

		if ($allowedTickets > 0) {
			$drawing->addHeader("Click on a ticket to buy  it, up to $allowedTickets more ($LOTTO_MAX_PERCENT%). Your chances of winning are: $winningChance");
		} else {
			$drawing->addHeader("You exceeded the maximum allowed tickets ($LOTTO_MAX_PERCENT%). Your chances of winning are: $winningChance");
		}
	}

	// HTML goodness.
	$html = "<h2>Play Lotto</h2>";
	$html .= $MyInfo->flush() . "<br>";

	if ($GotWinners) {
		$html .= $MyWins->flush() . "<br>";
	}

	// only include ticket table if we have tickets.
	if ($AreTickets) {
		$html .= ($drawing->flush());
	}

	// return the page.
	return ($html);
}
?>
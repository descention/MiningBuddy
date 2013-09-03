<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_buyTickets.php,v 1.10 2007/02/10 22:42:11 mining Exp $
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

function lotto_buyTickets() {

	// Set some needed variables.
	global $DB;
	global $MySelf;

	$ID = $MySelf->getID();
	$myMoney = getCredits($ID);
	$affordable = floor($myMoney / 1000000);
	
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Get my credits
	$MyStuff = $DB->getRow("SELECT lottoCredit, lottoCreditsSpent FROM users WHERE id='" . $MySelf->getID() . "'");
	$Credits = $MyStuff[lottoCredit];
	$CreditsSpent = $MyStuff[lottoCreditsSpent];

	// User submited this form already!
	if ($_POST[check]) {
		numericCheck($_POST[amount], 0, $affordable);

		if ($_POST[amount] == 0) {
			makeNotice("You cannot buy zero tickets.", "warning", "Too few tickets.", "index.php?action=lotto", "[whoops]");
		}

		confirm("Please authorize the transaction of " . number_format(($_POST[amount] * 1000000), 2) . " ISK in order to buy $_POST[amount] lotto credits.");

		// Get the old ticket count, and add the new tickets on top of those.			
		$oldCount = $DB->getCol("SELECT lottoCredit FROM users WHERE id='$ID' LIMIT 1");
		$newcount = $oldCount[0] + $_POST[amount];

		// Update the database to reflect the new ticket count.
		$check = $DB->query("UPDATE users SET lottoCredit='$newcount' WHERE id='$ID' LIMIT 1");

		// Check that we were successful.
		if ($DB->affectedRows() != 1) {
			makeNotice("I was unable to add $newcount tickets to $user stack of $count tickets! Danger will robonson, danger!", "error", "Unable to comply.");
		}

		// Make him pay!
		global $TIMEMARK;
		$transaction = new transaction($ID, 1, ($_POST[amount] * 1000000));
		$transaction->setReason("lotto credits bought");
		if ($transaction->commit()){
			// all worked out!
			makeNotice("Your account has been charged the amount of " . number_format(($_POST[amount] * 1000000), 2) . " ISK.", "notice", "Credits bought", "index.php?action=lotto", "[OK]");
		} else {
			// We were not successfull
			makeNotice("I was unable to add $newcount tickets to $user stack of $count tickets! Danger will robonson, danger!", "error", "Unable to comply.");
		}
	}

	// Prepare the drop-down menu.
	if ($affordable >= 1) {
		$ddm = "<select name=\"amount\">";
		for ($i = 1; $i <= $affordable; $i++) {
			if ($i == 1) {
				$ddm .= "<option value=\"$i\">Buy $i tickets</option>";
			} else {
				$ddm .= "<option value=\"$i\">Buy $i tickets</option>";
			}
		}
		$ddm .= "</select>";
	} else {
		// Poor user.
		$ddm = "You can not afford any credits.";
	}

	// Create the table.
	$table = new table(2, true);
	$table->addHeader(">> Buy lotto credits");
	$table->addRow();
	$table->addCol("Here you can buy lotto tickets for 1.000.000,00 ISK each. " .
	"Your account currently holds " . number_format($myMoney, 2) . " ISK, so " .
	"you can afford $affordable tickets. Please choose the amount of credits you wish " .
	"to buy.", array (
		"colspan" => 2
	));

	$table->addRow();
	$table->addCol("Your credits:");
	$table->addCol($Credits);
	$table->addRow();
	$table->addCol("Total spent credits:");
	$table->addCol($CreditsSpent);
	$table->addRow();
	$table->addCol("Purchase this many credits:");
	$table->addCol($ddm);
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Buy credits\">");
	$table->addRow("#060622");
	$table->addCol("[<a href=\"index.php?action=lotto\">Cancel request</a>]", array (
		"colspan" => 2
	));

	// Add some more html form stuff.
	$html = "<h2>Buy Lotto credits</h2>";
	$html .= "<form action=\"index.php\" method=\"POST\">";
	$html .= $table->flush();
	$html .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$html .= "<input type=\"hidden\" name=\"action\" value=\"lottoBuyCredits\">";
	$html .= "</form>";

	// Return the mess we made.
	return ($html);
}
?>
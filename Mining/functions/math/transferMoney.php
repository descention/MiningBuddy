<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/transferMoney.php,v 1.7 2008/01/12 22:14:02 mining Exp $
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
 
 function transferMoney() {
	
	// Globals
 	global $MySelf;
 	global $DB;
 	global $TIMEMARK;
 	$MyCredits = getCredits($MySelf->getID());
 	
 	// Can we afford even the most basic transactions?
 	if (!numericCheckBool($MyCredits, 0)) {
 		makeNotice("You can not afford any transaction.", "warning", "Out of money", "index.php?action=manageWallet", "[cancel]");
 	} 	
 	
 	// Did we supply an isk amount at all?
 	if ($_POST[amount] == "") {
 		makeNotice("You did not specify an ISK amount. Please go back, and try again.", "warning", "How much?", "index.php?action=manageWallet", "[cancel]");
 	}
 	
 	if (!is_numeric($_POST[amount])) {
 		makeNotice("The frog looks at you and your cheque with the amount of \"".$_POST[amount]."\". The frog is unsure how much ISK that is and instead decides to lick your face in a friendly manner, then it closes the teller and goes for lunch.", "warning", "Huh?");
 	}
 	
 	// Check for sanity.
 	if (!numericCheckBool($_POST[to], 0)) {
 		makeNotice("The supplied reciver is not valid.", "warning", "Invalid ID", "index.php?action=manageWallet", "[cancel]");
 	}

	if (!numericCheckBool($_POST[amount], 0)) {
 		makeNotice("You need to specify a positive ISK value.", "error", "Invalid amount", "index.php?action=manageWallet", "[cancel]");
 	}
 	
 	if (!numericCheckBool($_POST[amount], 0, $MyCredits)) {
 		makeNotice("You can not afford this transaction.", "warning", "Out of money", "index.php?action=manageWallet", "[cancel]");
 	}
 	
 	// Ok so now we know: The reciver is valid, the sender has enough money.
 	$from = "<br><br>From: " . ucfirst($MySelf->getUsername());
 	$to = "<br>To: ". ucfirst(idToUsername($_POST[to]));
 	$amount = "<br>Amount: " . number_format($_POST[amount], 2) . " ISK";
 	confirm("Please authorize this transaction:" . $from . $to . $amount);

	// Lets do it.
	$transaction = new transaction($_POST[to], 0, $_POST[amount]);
	$transaction->setReason("Cash transfer from " . ucfirst($MySelf->getUsername()) . " to " . ucfirst(idToUsername($_POST[to])));
	$transaction->isTransfer(true);
	$transaction->commit();
	
    // Send'em back.
    makeNotice($amount . " has been transfered from your into " . ucfirst(idToUsername($_POST[to])) . " account.", "notice", "Cash transfered", "index.php?action=manageWallet", "[OK]");
 }
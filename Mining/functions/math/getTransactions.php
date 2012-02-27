<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/getTransactions.php,v 1.16 2008/01/02 20:01:33 mining Exp $
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

function getTransactions($user) {

	// Database 4tw!
	global $DB;

	// Sanity!
	numericCheck($user, 0);

	// Grande Heading!
	$table = new table(6, true);
	$table->addHeader(">> Transaction log for " . ucfirst(idToUsername($user)));

	// Table columns descriptors.
	$table->addRow("#060622");
	$table->addCol("Date");
	$table->addCol("Transaction ID");
	$table->addCol("Banker");
	$table->addCol("Type");
	$table->addCol("Amount");
	$table->addCol("Reason");

	// ask the oracle.
	$transactions = $DB->getCol("SELECT COUNT(id) as count FROM transactions WHERE owner='" . $user . "' ORDER BY time DESC, id DESC");
	$transactions_count = $transactions[0];
	$transactions_pages = ceil(($transactions_count / 20));
	
	// No transactions yet.
	if ($transactions_count < 1) {
		return (false);
	}

	$currentPage = $_GET[walletPage];
	
	// Get the right amount of datasets from the dbase.
	if ($currentPage > 0 && is_numeric($currentPage)) {
		$min = (20 * $currentPage)-20;
	} else {
		$min = 0;
	}
	
	// Query the database accordingly
	$transactions = $DB->query("SELECT * FROM transactions WHERE owner='" . $user . "' ORDER BY time DESC, id DESC LIMIT $min,20");	
	
	while ($transaction = $transactions->fetchRow()) {
		$table->addRow();
		$table->addCol(date("d.m.y H:i:s", $transaction[time]));
		$table->addCol(str_pad($transaction[id], "6", "0", STR_PAD_LEFT));
		$table->addCol(ucfirst(idToUsername($transaction[banker])));

		switch ($transaction[type]) {
			case ("0") :
				$table->addCol("deposit");
				break;
			case ("1") :
				$table->addCol("withdrawal");
				break;
		}

		if ($transaction[amount] > 0) {
			$table->addCol("<font color=\"#00ff00\">" . number_format($transaction[amount], 2) . " ISK</font>");
		} else {
			$table->addCol("<font color=\"#ff0000\">" . number_format($transaction[amount], 2) . " ISK</font>");
		}

		$table->addCol(strtolower($transaction[reason]));
	}

	// Get the right next and previous pages.
	$currentPage = $_GET[walletPage];
	
	// if we have more than 1 page, show the navbar.
	if ($transactions_pages > 1) {
	
	// Handle first page: Static numbering.
	if ($currentPage < 1) {
		$next = 2;
	} else {
		// handle pages greater 2. Check if we have yet another page.
		if ($transactions_pages > ($currentPage)) {
			$next = $currentPage+1;
		}
		// All pages above 2 have a previous page.
		$prev = $currentPage-1;
	}
	
	$table->addRow("#060622");

	// Show backlink, unless we are at page 1.
	if ($prev) {
		// We have a previous page (at page > 1)
		$table->addCol("<a href=\"index.php?action=$_GET[action]&id=$_GET[id]&walletPage=$prev\">prev</a>", array("colspan"=>2));	
	} else {
	 	// No previos page (at page 1);
	 	$table->addCol(" ", array("colspan"=>2));
	}

	// Show direct page links.
	if ($transactions_pages >1) {
		for ($i=1; $i <= $transactions_pages; $i++) {
			if ($currentPage == $i) {			
				$text.= "[$i]";
			} else {
				$text.= "[<a href=\"index.php?action=$_GET[action]&id=$_GET[id]&walletPage=$i\">$i</a>]";
			}
		}
	}
	$table->addCol($text, array("colspan"=>2,"align"=>"center"));	
	
	// Next link
	if ($currentPage < $transactions_pages) {
		// We have a next page. (at page < n)
		$table->addCol("<a href=\"index.php?action=$_GET[action]&id=$_GET[id]&walletPage=$next\">next</a>", array("colspan"=>2, "align"=>right));		
	} else {
		// This was the last page. (at page n)
		$table->addCol(" ", array("colspan"=>2));
	}

	}
	
	$table->addHeader("If there are any problems with your transactions, contact your ceo immediatly.");

	return ($table->flush());
}
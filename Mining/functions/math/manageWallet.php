<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/manageWallet.php,v 1.9 2008/01/06 19:41:51 mining Exp $
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

function manageWallet() {

	// Globals
	global $MySelf;
	global $DB;
	$MyCredits = getCredits($MySelf->getID());

	// Get (recent?) transactions
	$html = getTransactions($MySelf->getID());

	if ($MyCredits > 0) {

		// Create the dropdown menu with all pilots.
		$NamesDS = $DB->query("SELECT DISTINCT username, id FROM users WHERE deleted='0' ORDER BY username");
		$ddm = "<select name=\"to\">";
		while ($name = $NamesDS->fetchRow()) {
			// Lets not allow transfers to self.
			if ($name[id] != $MySelf->getID()) {
				$ddm .= "<option value=\"" . $name[id] . "\">" . ucfirst($name[username]) . "</option>";
			}
		}
		$ddm .= "</select>";

		$tt = new table(2, true);
		$tt->addHeader(">> Transfer ISK");
		$tt->addRow("#060622");
		$tt->addCol("You can transfer ISK into another Pilots wallet by using this form.", array (
			"colspan" => 2
		));
		$tt->addRow();
		$tt->addCol("Transfer from:");
		$tt->addCol(ucfirst($MySelf->getUsername()));
		$tt->addRow();
		$tt->addCol("Transfer to:");
		$tt->addCol($ddm);
		$tt->addRow();
		$tt->addCol("Amount:");
		$tt->addCol("<input type=\"text\" name=\"amount\">");
		$tt->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Transfer money\">");

		// Create form stuff, and embed the table within.
		$transfer = "<form action=\"index.php\" method=\"POST\">";
		$transfer .= $tt->flush();
		$transfer .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
		$transfer .= "<input type=\"hidden\" name=\"action\" value=\"transferMoney\">";
		$transfer .= "</form>";

		// Create the payout form.
		$payout = new table(2, true);
		$payout->addHeader(">> Request payout");
		$payout->addRow("#060622");
		$payout->addCol("Fill out this form to request payout of ISK. An accountant will honor your request soon.", array (
			"colspan" => 2
		));
		$payout->addRow();
		$payout->addCol("Payout amount:");
		$payout->addCol("<input type=\"text\" name=\"amount\" value=\"" . $MyCredits . "\"> ISK");
		$payout->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"request payout\">");

		// Create form stuff, and embed the table within.
		$requestPayout = "<form action=\"index.php\" method=\"POST\">";
		$requestPayout .= $payout->flush();
		$requestPayout .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
		$requestPayout .= "<input type=\"hidden\" name=\"action\" value=\"requestPayout\">";
		$requestPayout .= "</form>";
	}

	/*
	* Show current requests
	*/
	$requests = $DB->query("SELECT * FROM payoutRequests WHERE payoutTime IS NULL AND applicant='" . $MySelf->getID() . "' ORDER BY time");

	$table = new table(4, true);
	$table->addHeader(">> Pending payout requests");

	$table->addRow("#060622");
	$table->addCol("request");
	$table->addCol("time");
	$table->addCol("amount");
	$table->addCol("Cancel");

	while ($request = $requests->fetchRow()) {
		$table->addRow();
		$table->addCol("#" . str_pad($request[request], "5", "0", STR_PAD_LEFT));
		$table->addCol(date("d.m.y H:i:s", $request[time]));
		$table->addCol(number_format($request[amount], 2) . " ISK");
		$table->addCol("<input type=\"checkbox\" name=\"".$request[request]."\" value=\"true\">");
		$haveRequest = true;
	}
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"cancel marked requests\">");
	
	$takeBack  = "<form action=\"index.php\" method=\"POST\">";
	$takeBack .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$takeBack .= "<input type=\"hidden\" name=\"action\" value=\"deleteRequest\">";
	$takeBack .= $table->flush();
	$rakeBack .= "</form>";

	/*
	 * Show fulfilled requests
	 */
	$requests = $DB->query("SELECT * FROM payoutRequests WHERE payoutTime IS NOT NULL AND applicant='" . $MySelf->getID() . "' ORDER BY time");
	$table_done = new table(5, true);
	$table_done->addHeader(">> Fulfilled payout requests");

	$table_done->addRow("#060622");
	$table_done->addCol("request");
	$table_done->addCol("time");
	$table_done->addCol("amount");
	$table_done->addCol("Payout time");
	$table_done->addCol("Paid by");

	while ($request = $requests->fetchRow()) {
		$table_done->addRow();
		$table_done->addCol("#" . str_pad($request[request], "5", "0", STR_PAD_LEFT));
		$table_done->addCol(date("d.m.y H:i:s", $request[time]));
		$table_done->addCol(number_format($request[amount], 2) . " ISK");
		$table_done->addCol(date("d.m.y H:i:s", $request[payoutTime]));
		$table_done->addCol(ucfirst(idToUsername($request[banker])));
		$haveOldRequests = true;
	}

	if ($html) {
		$html = "<h2>Your Wallet</h2>" . $html . "<br>" . $requestPayout . $transfer;

		if ($haveRequest) {
			$html .= $takeBack . "<br>";
		}

		if ($haveOldRequests) {
			$html .= $table_done->flush();
		}
		
	} else {
		$html = "<h2>Your Wallet</h2>Once your wallet has any transactions you can view the details here. And once you obtained a positive balance you can transfer money and request payouts.<br>";
	}
	return ($html);

}
?>
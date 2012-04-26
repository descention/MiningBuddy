<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/payout.php,v 1.35 2008/04/03 18:33:51 mining Exp $
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

function payout() {

	// Some globals needed.
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	global $IGB;
	global $IGB_VISUAL;

	// Are we allowed to do this?
	if (!$MySelf->isAccountant()) {
		makeNotice("You are not an accountant to your corporation. Access denied.", "error", "Access denied");
	}

	/*
	 * Amount of ISK owned.
	 */
	$iskOwned = new table(2, true);
	$iskOwned->addHeader(">> Outstanding ISK");

	// Load all unique members from the database.
	$uniqeMembers = $DB->query("SELECT DISTINCT id FROM users WHERE deleted='0' ORDER BY username ASC");

	// Create a row for each member.
	while ($id = $uniqeMembers->fetchRow()) {
		$playerCreds = getCredits($id['id']);

		// We need this later on...
		$allPeeps[$id['id']] = ucfirst(idToUsername($id['id']));

		// if the member has more or less than zero isk, list him.
		if ($playerCreds != 0) {
			$iskOwned->addRow();
			$iskOwned->addCol("<a href=\"index.php?action=showTransactions&id=" . $id['id'] . "\">" . $allPeeps[$id['id']] . "</a>");
			$iskOwned->addCol(number_format($playerCreds, 2) . " ISK");
		}
	}

	// Show the total isk owned.
	$outstanding = totalIskOwned();
	$iskOwned->addRow("#060622");
	$iskOwned->addCol(">> Total Outstanding ISK:");
	$iskOwned->addCol(totalIskOwned() . " ISK");

	/*
	 * Show a drop down menu to create a menu to see everyones transaction log.
	 */
	$freeSelect = new table(2, true);
	$freeSelect->addHeader(">> Lookup specific transaction log");

	// Create a PDM for all the peoples.	 
	foreach ($allPeeps as $peep) {
		$pdm .= "<option value=\"" . array_search($peep, $allPeeps) . "\">$peep</option>";
	}

	$freeSelect->addRow();
	$freeSelect->addCol("Show log of ", array (
		"align" => "right"
	));
	$freeSelect->addCol("<select name=\"id\">$pdm</select>");
	$freeSelect->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Lookup log in Database\">");
	unset ($pdm);

	/*
	 * Show current requests
	 */

	$requests = $DB->query("SELECT * FROM payoutRequests WHERE payoutTime IS NULL ORDER BY time DESC");

	if ($IGB && $IGB_VISUAL) {
		$table = new table(6, true);
	} else {
		$table = new table(5, true);
	}
	$table->addHeader(">> Pending payout requests");

	$table->addRow("#060622");
	$table->addCol("request");
	$table->addCol("applicant");
	if ($IGB && $IGB_VISUAL) {
		$table->addCol("right click menu");
	}
	$table->addCol("time");
	$table->addCol("amount");
	$table->addCol("Payout");

	while ($request = $requests->fetchRow()) {

		if ($IGB && $IGB_VISUAL) {
			$api = new api($request['applicant']);
//			$profile = new profile($request['applicant']);
			if ($api->valid() && ($IGB && $IGB_VISUAL)) {
				$rcm = " [<a href=\"showinfo:1378//" . $api->getCharacterID() . "\">RCM</a>]";
			}
		}
		$table->addRow();
		$table->addCol("#" . str_pad($request['request'], "5", "0", STR_PAD_LEFT));
		$table->addCol("<a href=\"index.php?action=showTransactions&id=$request[applicant]\">" . ucfirst(idToUsername($request['applicant'])) . "</a>");
		if ($IGB && $IGB_VISUAL) {
			$table->addCol($rcm);
		}
		$table->addCol(date("d.m.y H:i:s", $request['time']));
		
		if (getCredits($request['applicant']) < $request['amount']) {
			$class .= "red";
		}
		
		if ($IGB && $IGB_VISUAL) {
			$table->addCol("<input type=\"text\" class=\"$class\" name=\"dumb\" readonly value=\"" . number_format($request['amount'], 2) . "\"> ISK");
		} else {
			$table->addCol(number_format($request['amount'], 2) . " ISK", array("class"=>$class));
		}

		// Can the user still cover his request with cash?
		$table->addCol("<input type=\"checkbox\" name=\"" . $request['request'] . "\" value=\"true\">");
		$haveRequest = true;
		//} else {
		//	$table->addCol("<i>not enough ISK</i>");
		//}
	}
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Mark as paid\">");

	$funnyForm = "<form action=\"index.php\" method=\"POST\">";
	$funnyForm .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$funnyForm .= "<input type=\"hidden\" name=\"action\" value=\"payout\">";
	$funnyForm .= $table->flush();
	$funnyForm .= "</form>";

	/*
	 * Show fulfilled requests
	 */
	if (is_numeric($_GET['page']) && $_GET['page'] > 0) {
		$page = "LIMIT " . ($_GET['page'] * 20) . ", 20";
	}
	elseif ($_GET['page'] == "all") {
		$page = "";
	} else {
		$page = "LIMIT 20";
	}

	$requests = $DB->query("SELECT * FROM payoutRequests WHERE payoutTime IS NOT NULL ORDER BY time DESC $page");
	$table_done = new table(6, true);
	$table_done->addHeader(">> Fulfilled payout requests");

	$table_done->addRow("#060622");
	$table_done->addCol("request");
	$table_done->addCol("applicant");
	$table_done->addCol("time");
	$table_done->addCol("amount");
	$table_done->addCol("Payout time");
	$table_done->addCol("Authorized by");

	while ($request = $requests->fetchRow()) {
		$table_done->addRow();
		$table_done->addCol("#" . str_pad($request['request'], "5", "0", STR_PAD_LEFT));
		$table_done->addCol("<a href=\"index.php?action=showTransactions&id=$request[applicant]\">" . ucfirst(idToUsername($request['applicant'])) . "</a>");
		$table_done->addCol(date("d.m.y H:i:s", $request['time']));
		$table_done->addCol(number_format($request['amount'], 2) . " ISK");
		$table_done->addCol(date("d.m.y H:i:s", $request['payoutTime']));
		$table_done->addCol(ucfirst(idToUsername($request['banker'])));
		$haveOldRequests = true;
	}

	// The "show this many payouts"-part.
	$count = $DB->getCol("SELECT COUNT(request) FROM payoutRequests WHERE payoutTime IS NOT NULL");
	$countSteps = floor($count[0] / 20);
	$showMore = "Switch to page >> ";
	for ($i = 1; $i <= $countSteps; $i++) {
		$thisStep = str_pad($i, 2, "0", STR_PAD_LEFT);
		$showMore .= "[<a href=\"index.php?action=payout&page=" . $thisStep . "\">" . $thisStep . "</a>] ";
	}
	$showMore .= "[<a href=\"index.php?action=payout&page=all\">All</a>] ";
	$table_done->addHeader($showMore);

	$html = "<h2>Manage Payouts</h2>" . $iskOwned->flush() . "<br><br>";
	$html .= "<form action=\"index.php\" method=\"GET\">" . $freeSelect->flush() . "<input type=\"hidden\" name=\"action\" value=\"showTransactions\"></form><br>";

	if ($haveRequest) {
		$html .= $funnyForm . "<br>";
	} else {
		$html .= "<i>No open requests.</i><br><br>";
	}

	if ($haveOldRequests) {
		$html .= $table_done->flush();
	} else {
		$html .= "<br><i>No payouts at all.</i>";
	}

	return ($html);
}
?>
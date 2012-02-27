<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step8.php,v 1.4 2008/01/06 14:03:59 mining Exp $
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

// Calculate Payout: 

$joinedPeople = $DB->query("SELECT DISTINCT userid FROM joinups WHERE run ='$ID' AND status < '2'");
$ISK = getTotalWorth($ID, true);

if ($ISK > 0) {

	$payout_info = new table(3, true);
	$payout_info->addHeader(">> Payout Information");
	$payout_info->addRow("060622");
	$payout_info->addCol("Pilot", array (
		"bold" => true
	));
	$payout_info->addCol("Percent of Net", array (
		"bold" => true
	));
	$payout_info->addCol("Payout", array (
		"bold" => true
	));

	// get the payout array. Fun guaranteed.
	while ($peep = $joinedPeople->fetchRow()) {
		$payoutArray[idToUsername($peep[userid])] = calcPayoutPercent($ID, $peep[userid]);
	}

	// Calulate the percent-modifier.
	$percentModifier = 100 / array_sum($payoutArray);

	// Apply the modifier to the percentage.
	$names = array_keys($payoutArray);
	foreach ($names as $name) {
		$percent = $payoutArray[$name] * $percentModifier;

		$payout = ($ISK / 100) * $percent;
		$payout_info->addRow();
		
		$payout_info->addCol(makeProfileLink(usernameToID($name)));
		$payout_info->addCol(number_format($percent, 2) . "%");
		
		if($MySelf->isAccountant()){
			$payout_info->addCol("<a href=\"index.php?action=showTransactions&id=".usernameToID($name)."\">".number_format($payout, 2) . " ISK</a>");
		} else {
			$payout_info->addCol(number_format($payout, 2) . " ISK");
		}
		$totalPayout = $totalPayout + $payout;
		$totalPercent = $totalPercent + $percent;
	}

	$payout_info->addRow("060622");
	$payout_info->addCol("TOTAL", array (
		"bold" => true
	));
	$payout_info->addCol(number_format($totalPercent, 2) . "%");
	$payout_info->addCol(number_format($totalPayout, 2) . " ISK");
}
?>
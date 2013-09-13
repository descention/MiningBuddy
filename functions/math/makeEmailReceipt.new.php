<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/makeEmailReceipt.new.php,v 1.3 2008/01/06 19:41:51 mining Exp $
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

function makeEmailReceipt($runid, $array) {

	// Set variables.
	global $DB;
	global $DBORE;
	global $ORENAMES;
	global $MB_EMAIL;
//Edit Starts Here	
	global $DBSHIP;
	global $SHIPNAMES;
//Edit Ends Here	
	global $VERSION;
	global $URL;

	// Load the run
	$RUN = $DB->query("SELECT * FROM runs WHERE id='$runid' LIMIT 2");
	if ($RUN->numRows() != 1) {
		// This run does not exist!
		return;
	} else {
		$RUN = $RUN->fetchRow();
	}

	// Load the ore values
	$oreValues = $DB->query("SELECT * FROM orevalues WHERE id='".$RUN[oreGlue]."' LIMIT 1");
	$oreValues = $oreValues->fetchRow();
//Edit Starts Here	
	// Load the ship values
	$shipValues = $DB->query("SELECT * FROM shipvalues WHERE id='".$RUN[shipGlue]."' LIMIT 1");
	$shipValues = $shipValues->fetchRow();
//Edit Ends Here
	// Get total number of people who joined.
	$totalPeople = $DB->getCol("SELECT count(DISTINCT userid) FROM joinups WHERE run='$runid'");
	$totalPeople = $totalPeople[0];

	/*
	 * Taxes, net value etc
	 */
	$grossValue = getTotalWorth($runid);
	$corpTax = $DB->getCol("SELECT corpkeeps FROM runs WHERE id='$runid' LIMIT 1");
	$corpTax = $corpTax[0];
	$taxes = ($grossValue * $corpTax) / 100;
	$netValue = $grossValue - $taxes;
	$myShareGross = $grossValue / $totalPeople;

	// No ores mined. Bye-bye.
	if ($grossValue < 1) {
		return;
	}

	/*
	 * Get the longest name of the ores.
	 */
	foreach ($ORENAMES as $howlong) {
		$length = strlen($howlong);
		if ($length > $winner) {
			$winner = $length;
		}
	}

	/*
	 * Get all the ores.
	 */
	foreach ($DBORE as $ORE) {
		if ($RUN[$ORE] > 0) {
			$oreType = str_pad(array_search($ORE, $DBORE), $winner, " ");
			$oreAmount = str_pad(number_format($RUN[$ORE]), 11, " ");
			$ppu = $oreValues[$ORE . "Worth"];
			$oreValue = str_pad((number_format($ppu) . " ISK"), 11, " ");

			$remainder = 70 - (strlen($oreType) + strlen($oreAmount) + strlen($oreValue));

			$oreTotalValue = str_pad(((number_format($ppu * $RUN[$ORE])) . " ISK"), $remainder, " ", STR_PAD_LEFT);

			$l1 = strlen($oreAmount);
			$l1 = strlen($oreValue);
			$l1 = strlen($oreTotalValue);

			if ($OreLine) {
				$OreLine .= "\n";
			}
			$OreLine .= $oreType . $oreAmount . $oreValue . $oreTotalValue;
		}
	}

	/*
	 * Replace the placeholders
	 */
	$email = new email("receipt");
	$temp = $email->getTemplate();
	$temp = str_replace("{{ID}}", str_pad($runid, 5, "0", STR_PAD_LEFT), $temp);
	$temp = str_replace("{{ORESMINED}}", $OreLine, $temp);
	$temp = str_replace("{{VALUE}}", number_format($grossValue) . " ISK", $temp);
	$temp = str_replace("{{CORPTAXES}}", number_format($taxes) . " ISK", $temp);
	$temp = str_replace("{{NETVALUE}}", number_format($netValue) . " ISK", $temp);
	$temp = str_replace("{{GROSSSHARE}}", number_format($myShareGross) . " ISK", 	$temp	);

	$template = $temp;
	/*
	 * This ends the part thats generic for everyone. Now the personalized stuff.
	 */
	$Atendees = $DB->query("SELECT DISTINCT userid FROM joinups WHERE run='$runid'");
	while ($atendee = $Atendees->fetchRow()) {
		// Reset the email back to the template.
		$temp = $template;
		
		/*
		 * PROBLEM
		 * 
		 * Template is changed in email class, further modding in this loop
		 * would require rewrite of either email class or this function.
		 * 
		 */
		 die("PROBLEM");
		 
		// Do some personalized stuff.
		$pilot = idToUsername(($atendee[userid]));
		$temp = str_replace("{{USERNAME}}", ucfirst($pilot), $temp);
		$temp = str_replace("{{ACCOUNTBALANCE}}", str_pad("BALANCE: " . number_format(getCredits($atendee[userid])), 66, " ", STR_PAD_LEFT) . " ISK", $temp);
		$myShare = $array[$atendee[userid]];
		$temp = str_replace("{{NETSHARE}}", number_format($myShare) . " ISK", $temp);

		/*
		 * transactions
		 */
		$transactions = $DB->query("SELECT * FROM transactions WHERE owner ='$atendee[userid]' ORDER BY id DESC LIMIT 10");
		if ($transactions->numRows() > 0) {
			while ($trans = $transactions->fetchRow()) {
				// time type amount reason
				$transLine .= date("m.d.y h:i:", $trans[time]);
				if ($trans[type]) {
					$transLine .= " [W]";
				} else {
					$transLine .= " [D]";	
				}
				
				$transLine .= " \"".substr($trans[reason], 0, 33)."\"";
				$length = strlen($transLine);
				$remainder = 70 - $length;				
				$transLine .= str_pad((number_format($trans[amount]). " ISK"), $remainder, " ", STR_PAD_LEFT);
				
				// Add the line to the block.
				if ($transBlock) {
					$transBlock .= "\n";
				}
				$transBlock .= $transLine;
				unset($transLine);
				
			}
		} else {
			$transLine="No recent transactions.";
		}
		$temp = str_replace("{{ACCOUNT}}", $transBlock, $temp);

		// Mail, if opt-in.		
		$userInfo = $DB->query("SELECT username, id, optIn, email FROM users WHERE id='".$atendee[userid]."' AND deleted='0'");
		$userInfo = $userInfo->fetchRow();
				
		if ($userInfo[email] && $userInfo[optIn]) {
			$to = $userInfo[email];
			$subject = "MiningBuddy Payout";
			$message = $email;
			$DOMAIN = $_SERVER['HTTP_HOST'];
			$headers = "From:" . $MB_EMAIL;
			mail($to,$subject,$message,$headers);
		}
	}
}
?>
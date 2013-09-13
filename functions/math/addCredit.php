<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/addCredit.php,v 1.9 2008/01/02 20:01:33 mining Exp $
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

/*
 * This adds a transaction log to the database, 
 * ADDING credits to the users account.
 */

function addCredit($userID, $banker, $credit, $runID) {
	
	// Sane?
	numericCheck($userID, 0);
	numericCheck(abs($credit), 0);
	numericCheck($banker, 0);

	// Globals, YAY!
	global $DB;
	global $TIMEMARK;

	// Create a transaction.
	if($credit >= 0){
		$transaction = new transaction($userID, 0, $credit);
		$transaction->setReason("operation #".str_pad($runID, 5, "0", STR_PAD_LEFT)." payout");
	}else{
		$transaction = new transaction($userID, 1, abs($credit));
		$transaction->setReason("operation #".str_pad($runID, 5, "0", STR_PAD_LEFT)." charge");	
	}
	$state = $transaction->commit();
	
	if ($state) {
		return (true);
	} else {
		makeNotice("Unable to grant money to user #$userID!", "error", "Unable to comply!");
	}
}
?>

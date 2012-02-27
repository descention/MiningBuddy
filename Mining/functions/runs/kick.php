<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/kick.php,v 1.7 2008/01/02 20:01:32 mining Exp $
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
 * This kicks / bans / removes a user from a run.
 */

function kick() {

	// Set some vars.
	$joinID = $_GET[joinid];
	$state = $_GET[state];
	numericCheck($joinID, 0);
	numericCheck($state, 0, 3);
	global $DB;
	global $MySelf;
	global $TIMEMARK;

	// Get the RunID.
	$runID = $DB->getCol("SELECT run, joined FROM joinups WHERE id='$joinID' LIMIT 1");
	$runID = $runID[0];

	// Are we allowed to kick/ Ban?
	if ((runSupervisor($runID) != $MySelf->getUsername()) && !$MySelf->isOfficial()) {
		makeNotice("You are not allowed to kick/ban/remove people from a run. Only the run supervisor or a corporation official is allowed to do that.", "warning", "Not allowed");
	}

	// get the userid (to be kicked)
	$kicked = $DB->getCol("SELECT userid FROM joinups WHERE id='$joinID' LIMIT 1");
	$kicked = $kicked[0];

	// We cant kick ourselves.
	if ($kicked == $MySelf->getID()) {
		makeNotice("You can not remove, kick or ban yourself. Get someone else to do the job for you.", "notice", "Can not comply");
	}

	// get confirmations.
	switch ($state) {
		case ("1") :
			confirm("Are you sure you want to remove " . ucfirst(idToUsername($kicked)) . "?<br>" .
			"By removing the user he or she retains all shares of his ISK and is honorably discharged from this operation.");
			break;
		case ("2") :
			confirm("Are you sure you want to kick " . ucfirst(idToUsername($kicked)) . "?<br>" .
			"By kicking the user he or she loses all shares of his ISK and is dishonorably discharged from this operation.");
			break;
		case ("3") :
			confirm("Are you sure you want to ban " . ucfirst(idToUsername($kicked)) . "?<br>" .
			"By banning the user he or she loses all shares of his ISK and is dishonorably discharged from this operation and additionally the user can never rejoin his operation.");
			break;
	}
	
	/* 
	 * Logic bomb work-around
	 * If a user joins an op before it starts, and the leaves during the operation
	 * he will receive huge bonuses while all the others will get negative amounts.
	 * So we have to...
	 * 
	 * 1. Check if the op has started yet (current time < operation start)
	 *  If "no" then we are not affected by the logic bomb.
	 *  
	 *  If "yes" then we need to set the kicktime AND the jointime to the current time.
	 *  Why? If we just set the kicktime to the jointime then the "kicked at" time will
	 *  always show the time of the op launch, never the real kick time. Also, the
	 *  duration is always zero seconds, so the user will never receive any share from
	 *  this run.
	 */
	 if ($TIMEMARK < $kicked[joined]) {
	 	$partedTime = $kicked[joined];
	 } else {
	 	$partedTime = $TIMEMARK;
	 }
	 
	// Now lets handle kicks, bans and removals.
	$DB->query("update joinups set remover = '" . $MySelf->getID() . "' where run = '$runID' and userid = '$kicked' and parted IS NULL");
	$DB->query("update joinups set status = '$state' where run = '$runID' and userid = '$kicked' and parted IS NULL");
	$DB->query("update joinups set parted = '$partedTime' where run = '$runID' and userid = '$kicked' and parted IS NULL");

	// Thats it, for now.	
	header("Location: index.php?action=show&id=$runID");
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/joinRun.php,v 1.22 2008/01/02 20:01:32 mining Exp $
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
 * This allows the user to join a run.
 */

function joinRun() {
	// Access the globals.    
	global $DB;
	global $TIMEMARK;
	global $MySelf;
	$runid = (int) $_GET[id];
	$userid = $MySelf->GetID();

	// Are we allowed to join runs?
	if (!$MySelf->canJoinRun()) {
		makeNotice("You are not allowed to join mining operations. Please ask your CEO to unblock your account.", "error", "Forbidden");
	}

	// Is $runid truly an integer?
	numericCheck($runid);

	// Is the run still open?
	if (!miningRunOpen($runid)) {
		makeNotice("This mining operation has been closed!", "warning", "Can not join", "index.php?action=show&id=$runid");
	}

	// Are we banned from the run?
	$State = $DB->getCol("SELECT status FROM joinups WHERE run='$runid' and userid='" . $MySelf->getID() . "'ORDER BY id DESC LIMIT 1");
	$State = $State[0];

	switch ($State) {
		case ("2") :
			// We have been kicked.
			$kicked = true;
			break;
		case ("3") :
			// We have been banned!
			if ((runSupervisor($runid) == $MySelf->getUsername()) || $MySelf->isOfficial()) {
				$banned = "You have been banned from this operation but your rank overrides this block.";
			} else {
				makeNotice("You have been banned from this operation. You can not rejoin it.", "warning", "You are banned.", "index.php?action=list", "[cancel]");
			}
			break;
	}

	// Is the run locked?
	if (runIsLocked($runid)) {
		makeNotice("You can not join this run as this run has been locked by " . runSupervisor($runid) . ".", "notice", "Mining operation locked", "index.php?action=show&id=$runid", "[Cancel]");
	}
	
	// Join with shiptype.
	if (!$_GET['confirmed-ship']) {
		$table = new table(1, true);
		$table->addHeader(">> Join an Operation");

		// If we have been kicked, inform the user.
		if ($kicked) {
			$table->addRow("#880000");
			$table->addCol("Warning: You have been recently kicked. Please check if you are allowed to rejoin to avoid a ban.");
		}

		// If we are banned but an official, inform the user.
		if ($banned) {
			$table->addRow("#880000");
			$table->addCol($banned);
		}

		$table->addRow();
		$table->addCol($form . "Join the Operation in " . ucfirst(getLocationOfRun($runid)) . ".");
		$table->addRow();
		$table->addCol("You have requested to join mining operation #$runid. Please choose the shipclass " .
		"you are going to join up with.");
		$table->addRow();
		$table->addCol("Shiptype: " . $hiddenstuff . joinAs(), array (
			"align" => "center"
		));
		$table->addRow("#444455");
		$table->addCol("<input type=\"submit\" name=\"submit\" value=\"Join mining operation\">" . $form_end, array (
			"align" => "center"
		));

		$page = "<h2>Join an Operation.</h2>";
		$page .= "<form action=\"index.php\" method=\"GET\">";
		$page .= "<input type=\"hidden\" name=\"id\" value=\"$runid\">";
		$page .= "<input type=\"hidden\" name=\"confirmed-ship\" value=\"true\">";
		$page .= "<input type=\"hidden\" name=\"confirmed\" value=\"true\">";
		$page .= "<input type=\"hidden\" name=\"multiple\" value=\"true\">";
		$page .= "<input type=\"hidden\" name=\"action\" value=\"joinrun\">";

		$page .= ($table->flush());
		$page .= "</form>";
		return ($page);

	}

	// Sanitize the Shiptype.
	global $SHIPTYPES;
	$ShiptypesCount = count($SHIPTYPES);
	if (!numericCheck($_GET[shiptype], 0, $ShiptypesCount)) {
		makeNotice("The shiptype you tried to join up with is invalid, please go back, and try again.", "warning", "Shiptype invalid!", "index.php?action=show&id=$_GET[id]");
	} else {
		$shiptype = $_GET[shiptype];
	}
	
	// Warn the user if he is already in another run.
	$joinedothers = $DB->query("select run from joinups where userid='$userid' and parted IS NULL order by run");

	// And check for that just now.
	if ($joinedothers->numRows() > 0) {
		confirm("You joined another mining operation already!<br>Are you sure you want to join multiple runs at the same time?");
	}

	// Get the correct time to join (in case event hasnt started yet)
	$startOfRun = $DB->getCol("SELECT starttime FROM runs WHERE id='$runid' LIMIT 1");
	if ($startOfRun[0] > $TIMEMARK) {
		$time = $startOfRun[0];
	} else {
		$time = $TIMEMARK;
	}

	// Dont allow him to join the same mining run twice.
	if (userInRun($MySelf->getID(), "$runid") == "none") {

		// Mark user as joined.
		$DB->query("insert into joinups (userid, run, joined, shiptype) values (?,?,?,?)", array (
			"$userid",
			"$runid",
			"$time",
			"$shiptype"
		));

		// Forward user to his joined run.
		makeNotice("You have joined the Mining Operation.", "notice", "Joining confirmed", "index.php?action=show&id=$id");

	} else {

		// Hes already in that run.
		makeNotice("You are already in that mining run!", "notice", "Joinup not confirmed", "index.php?action=show&id=$id");

	}

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/addHaul.php,v 1.20 2008/01/05 14:47:38 mining Exp $
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
 * This adds a haul to the database.
 */

function addHaul() {
	// Globals.
	global $DB;
	global $DBORE;
	global $ORENAME_STR;
	global $TIMEMARK;
	global $MySelf;
	global $STATIC_DB;
	
	// Some more settings we need
	$userID = $MySelf->getID();

	// is the POST[id] truly a number?
	numericCheck($_POST[id]);
	$ID = sanitize($_POST[id]);

	// Are we allowed to haul?
	if (!$MySelf->canAddHaul()) {
		makeNotice("You are not allowed to haul to runs!", "error", "forbidden");
	}

	// Is the run still open?
	if (!miningRunOpen($ID)) {
		makeNotice("This mining operation has been closed!", "warning", "Can not join");
	}

	// Is the user in the run?
	if (userInRun($MySelf->getUsername(), "$ID") == "none") {
		makeNotice("You need to join that run before you can haul to it!", "error", "Need to join", "index.php?action=show&id=$ID");
	}
	
	// Mr. Proper
	$location = sanitize($_POST[location]);
	$location2 = sanitize($_POST[location2]);
	
	// Use manual input, if given.
	if ($location2) {
		$location = $location2;
	}
	
	// We dont accept empty locations.
	if ($location == "") {
		makeNotice("You need to supply a target location!", "error", "Commit haul denied.", "index.php?action=addhaul", "[Cancel]");
	}

	// Get the current ore amount for the selected run.
	$results = $DB->query("select * from runs where id='$ID' limit 1");

	/* Even tho its only one row (result) we are going to loop
	* through it. Just to be on the safe side. While we are at it,
	* we add the submited ore amount to the already stored amount.
	*
	* Note: I explicitly *allow* negative amounts to be "added", in
	*       case the hauler got destroyed on his way back.
	*/
	/*
	while ($row = $results->fetchRow()) {
		foreach ($DBORE as $ORE) {
			$newcount = $row[$ORE] + $_POST[$ORE]; 			
			$DB->query("update runs set $ORE = '" . $newcount . "' where id = '$ID'");
		}
	}
	*/
	/*
	* But wait! There is more!
	* Someone hauled our ore, lets record that in the
	* hauled database, along with a timestamp and whatever
	* he hauled.
	*/

	// Lets create the raw entry fist.
	
	
	$OPTYPE = $DB->getCol("select optype from runs where id = $ID");
	$OPTYPE = $OPTYPE[0];
	
	// Now loop through all the ore-types.
	foreach ($_POST as $ORE => $QTY) {
		if($ORE=="id"){continue;}
		if(isset($STATIC_DB)){
			$oreResult = $DB->query("select count(typeName) as v from $STATIC_DB.invTypes where friendlyName = '$ORE'");
			// Check the input, and insert it!
			$validOre = $oreResult->fetchRow();
			$skipOreCheck = false;
		}else{
			$skipOreCheck = true;
		}
		if (($skipOreCheck || $validOre[v] > 0) && (!empty ($QTY)) && is_numeric($QTY) && $QTY > 0) {

			// Is that ore-type actually enabled?
			if (getOreSettings($ORE,$OPTYPE) == 0 && $OPTYPE != "Shopping") {
				makeNotice("Your corporation has globally disabled the mining and hauling of $ORE. Please ask your CEO to re-enable $ORE globally.", "error", "$ORE disabled!", "index.php?action=show&id=$ID", "[back]");
			} else if( $OPTYPE == "Shopping" ){
				$QTY = $QTY * -1;
			}

			// Now insert the database.
			$DB->query("insert into hauled (miningrun, hauler, time, location, Item, Quantity) values (?,?,?,?,?,?)", array (
				"$ID",
				"$userID",
				"$TIMEMARK",
				"$location",
				"$ORE",
				"$QTY"
			));
			//$DB->query("UPDATE hauled SET Item = '$ORE', Quantity = '$_POST[$ORE]' WHERE time ='$TIMEMARK' and hauler = '$userID'");
			$changed = $changed + $DB->affectedRows();
		}
	}

	// Delete the haul again if nothing (useful) was entered.
	if ($changed < 1) {
		makeNotice("No valid Ore information found in your query, aborted.", "warning", "Haul not accepted", "index.php?action=show&id=$ID", "[cancel]");
	}

	/*
	 * All done.
	 */
	makeNotice("Your hauling information has been entered into the database.", "notice", "Hauling recorded.", "index.php?action=show&id=$ID");
}
?>

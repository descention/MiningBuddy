<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step7.php,v 1.27 2008/01/06 14:03:59 mining Exp $
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

// Are there any hauls at all?
if (getTotalHaulRuns($ID) > 0) {

	// Ask the oracle.
	if ($_GET[detailed] != true){
		$limit = "6";
	}
	
	$haulingDB = $DB->query("select * from hauled where miningrun = '$ID' group by time ORDER BY time DESC");
	
	// Create the table header.
	$hauled_information = new table(4, true);
	$hauled_information->addHeader(">> Transport Manifest");
	$hauled_information->addRow("#060622");
	$hauled_information->addCol("Hauler", array (
		"bold" => true
	));
	$hauled_information->addCol("Time", array (
		"bold" => true
	));
	$hauled_information->addCol("Location", array (
		"bold" => true
	));
	$hauled_information->addCol("Freight", array (
		"bold" => true
	));

	// Delete uneeded vars.
	unset ($temp);

	// Lets loop through the results!
	while ($row = $haulingDB->fetchRow()) {

		// The who hauled to where when stuff.
		$hauled_information->addRow(false, top);
		$hauled_information->addCol(makeProfileLink($row[hauler]));
		$hauled_information->addCol(date("H:i:s", $row[time]));
		$system = new solarSystem($row[location]);
		$hauled_information->addCol(ucfirst($system->makeFancyLink()));

		/* 
		 * Now we loop through all the ore in the hauled database (result)
		 * and print a Oretype: Amount for each Oretype that has an amount
		 * greater or lesser than zero, but not zero.
		 */

		$oc = 1;
		$singleHaulDB = $DB->query("select Item, Quantity from hauled where miningrun = '$ID' and time = $row[time] ORDER BY Item");
		while ($haul = $singleHaulDB->fetchRow()) {
			$ORE = $haul[Item];
			
			if ($haul[Quantity] > 0) {
				$temp .= number_format($haul[Quantity], 0) . " &tab;" . array_search($ORE, $DBORE) . "<br>";
			}
			elseif ($haul[Quantity]) {
				// Negative amount (storno)
				$temp .= "<font color=\"#ff0000\">" . number_format($haul[Quantity], 0) . " " . array_search($ORE, $DBORE) . "</font><br>";
			}
			
		}
		$oc++;
		$hauled_information->addCol($temp);
		unset ($temp);
		if($oc > $limit)
			break;
	}
	
	
	
	// offer full view.	
	if ($limit) {
		$hauled_information->addHeader("Only the 6 most recent hauls are shown. [<a href=\"index.php?action=show&id=".$ID."&detailed=true\">show all<a>]");
	} else {
		$hauled_information->addHeader("All hauls are shown. [<a href=\"index.php?action=show&id=".$ID."\">show only recent<a>]");
	}
}
?>
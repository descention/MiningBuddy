<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeShipValue.php,v 1.19 2008/01/02 20:01:32 mining Exp $
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

// Prints the change form to edit the ship values.
function makeShipValue() {
	// Get the globals.
	global $TIMEMARK;
	global $SHIPTYPES;
	global $DBSHIP;
	global $DB;

	// load the values.
	$shipvaluesDS = $DB->query("select * from shipvalues order by id DESC limit 1");
	$shipvalues = $shipvaluesDS->fetchRow();

	// Create the table.
	$table = new table(6, true); //(8, true)
	$table->addHeader(">> Manage ship values (Values may be as little as 0.01% and as high as 999.99%)", array (
		"bold" => true,
		"colspan" => 6 //8
	));

	$table->addRow();
	$table->addCol("Ship Type", array (
		"colspan" => 2,
		"bold" => true
	));
//	$table->addCol("Enabled", array (
//		"bold" => true
//	));
	$table->addCol("Value", array (
		"bold" => true
	));
	$table->addCol("Ship Type", array (
		"colspan" => 2,
		"bold" => true
	));
//	$table->addCol("Enabled", array (
//		"bold" => true
//	));
	$table->addCol("Value", array (
		"bold" => true
	));

	// How many Ships are there in total? Ie, how long has the table to be?
	$tableLength = ceil(count($SHIPTYPES) / 2) - 2;

	for ($i = 0; $i <= $tableLength; $i++) {

		$table->addRow();
		$SHIP = $SHIPTYPES[$i];

		// Ship columns for LEFT side.
		$table->addCol("<img width=\"32\" height=\"32\" src=\"./images/ships/ship.png\">");
		$table->addCol($SHIP);
//		if (getShipSettings($DBSHIP[$SHIP])) {
//			$table->addCol("<input name=\"" . $DBSHIP[$SHIP] . "Enabled\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
//		} else {
//			$table->addCol("<input name=\"" . $DBSHIP[$SHIP] . "Enabled\" value=\"true\" type=\"checkbox\">");
//		}
		$table->addCol(("<input type=\"text\" name=\"$DBSHIP[$SHIP]\"" . "size=\"6\" value=\"" . number_format($shipvalues[$DBSHIP[$SHIP] . Value]*100,2) . "\">") . " %");

		// Ship columns for RIGHT side.
		$SHIP = $SHIPTYPES[$i + $tableLength + 1];
		
		if ($SHIP != "") {
			$table->addCol("<img width=\"32\" height=\"32\" src=\"./images/ships/ship.png\">");
			$table->addCol($SHIP);
//			if (getShipSettings($DBSHIP[$SHIP])) {
//				$table->addCol("<input name=\"" . $DBSHIP[$SHIP] . "Enabled\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
//			} else {
//				$table->addCol("<input name=\"" . $DBSHIP[$SHIP] . "Enabled\" value=\"true\" type=\"checkbox\">");
//			}
			$table->addCol(("<input type=\"text\" name=\"$DBSHIP[$SHIP]\"" . "size=\"6\" value=\"" . number_format($shipvalues[$DBSHIP[$SHIP] . Value]*100,2) . "\">") . " %");
		} else {
			$table->addCol("");
			$table->addCol("");
			$table->addCol("");
//			$table->addCol("");
		}

	}

	$form .= "<input type=\"hidden\" name=\"action\" value=\"changeship\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$form .= "<input type=\"submit\" name=\"change\" value=\"Modify ship settings\">";
	$table->addHeaderCentered($form, array (
		"colspan" => 6, //8
		"align" => "center"
	));

	// return the page
	return ("<h2>Modify ship settings</h2><form action=\"index.php\"method=\"post\">" . $table->flush());
}
?>
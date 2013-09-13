<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeNewOreRunPage.php,v 1.48 2008/01/06 19:41:51 mining Exp $
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

// prepares the add new ore run form.
function makeNewOreRunPage() {
	// Load the globals.
	global $VERSION;
	global $SITENAME;
	global $TIMEMARK;
	global $ORENAMES;
	global $DBORE;
	global $DB;
	global $MySelf;

	$locationPDM = "";
	// We need a list of all the previous run locations.
	$locations = $DB->query("SELECT DISTINCT location FROM runs ORDER BY location");
	if ($locations->numRows() > 0) {
		while ($location = $locations->fetchRow()) {
			$locationPDM .= "<option value=\"" . $location['location'] . "\">" . $location['location'] . "</option>";
		}
		$locationPDM = "<select name=\"locations\">" . $locationPDM . "</select>";
	}

	// Table
	$table = new table(2, true);

	$table->addHeader(">> Create a new operation");

	$table->addRow();
	// Field: Location.
	$table->addCol("Location of Operation:");
	if ($locationPDM) {
		// We have at least one possible System we hauled before.		
		$table->addCol($locationPDM . " -or- <input type=\"text\" name=\"location\">");
	} else {
		// There are not target systems in the database.
		if (getConfig("trustSetting") > 0) {
			$table->addCol("<input type=\"text\" value=\"" . $_SERVER['HTTP_EVE_SOLARSYSTEMNAME'] . "\" name=\"location\">");
		} else {
			$table->addCol("<input type=\"text\" name=\"location\">");
		}
	}
	
	$pdm = "";
	// Field: Officer in Charge
	if ($MySelf->isOfficial()) {
		$SeniorUsers = $DB->getCol("SELECT DISTINCT username FROM users WHERE canCreateRun = 1 AND deleted='0' ORDER BY username");
		foreach ($SeniorUsers as $senior) {
			if ($MySelf->getUsername() == "$senior") {
				$pdm .= "<option value=\"$senior\" selected>" . ucwords($senior) . "</option>";
			} else {
				$pdm .= "<option value=\"$senior\">" . ucwords($senior) . "</option>";
			}
			$seniorUsersPDM = "<select name=\"supervisor\">" . $pdm . "</select>";
		}
	} else {
		// In case the user is not a senior member he can not change the officer in charge.
		$seniorUsersPDM = ucfirst($MySelf->getUsername());
		$seniorUsersPDM .= "<input type=\"hidden\" name=\"supervisor\" value=\"" . $MySelf->getUsername() . "\">";
	}

	// We have no senior member (aka: people who may start runs)
	if (!$seniorUsersPDM) {
		makeNotice("No one from your current users may create or lead a mining operation. Please give out appropiate permissions.", "warning", "Insufficient Rights");
	} else {
		$table->addRow();
		$table->addCol("Executing Officer:");
		$table->addCol($seniorUsersPDM);
	}
	
	$table->addRow();
	$table->addCol("Op Type:");
	$OPTYPE = isset($_REQUEST['optype'])?$_REQUEST['optype']:"";
	
	$ops = $DB->getAll("select opName from opTypes;");
	if($DB->isError($ops)){
		die($ops->getMessage());
	}
	$opSelect = "<select name='optype' onChange='window.location = \"?action=newrun&optype=\"+this.value'>\n";
	$opSelect .= "<option value=''>Standard</option>\n";
	foreach($ops as $op){
		$default = $op['opName'] == $OPTYPE?"selected":"";
		$opSelect .= "<option $default value='".$op['opName']."'>".$op['opName']."</option>\n";
	}
	$opSelect .= "</select>";
	
	$table->addCol($opSelect);
	
	// Field: Corporation keeps.
	$table->addRow();
	$table->addCol("Corporation keeps:");

	// Get the average amount.
	if($MySelf->isOfficial()){
	if (!getConfig("defaultTax") && $OPTYPE != "Shopping") {
		// No default tax has been defined in the config file, generate our own.		
		$tax = $DB->getCol("SELECT AVG(corpKeeps) AS tax FROM runs;");
		$tax = round($tax[0]);

		// in case there are no taxes yet AND no default has been set.
		if (!$tax) {
			$tax = "15";
		}
	} else if ($OPTYPE == "Shopping"){
		$tax = "0";
	} else {
		// Set the default tax, according to config.
		$tax = getConfig("defaultTax");
	}
	$table->addCol("<input readonly=\"readonly\" type=\"text\" maxlength=\"3\" value=\"$tax\" size=\"4\" name=\"corpkeeps\">% of gross value.");
	} else {
		$table->addCol("As this is not an official Op, no tax is deducted.");
	}

	// Give option to make this run official.
	if ($MySelf->isOfficial()) {
		$table->addRow();
		$table->addCol("Official Run:");
		$table->addCol("<input type=\"checkbox\" name=\"isOfficial\" checked=\"checked\" >Tick box if this is an official mining run.");
	}

	// Field: Starttime.
	$table->addRow();
	$table->addCol("Starttime:");

	// Get a time-array and do the human friendly part.
	// Funnies: We always want to use "00" as the minute, and always at the start of the
	// NEXT hour.
	$times = humanTime("toHuman", ($TIMEMARK +3600));

	$timefield = "<input type=\"text\" name=\"ST_day\"    size=\"4\" maxlength=\"2\" value=\"" . $times['day'] . "\">." .
	"<input type=\"text\" name=\"ST_month\"  size=\"4\" maxlength=\"2\" value=\"" . $times['month'] . "\">." .
	"<input type=\"text\" name=\"ST_year\"   size=\"6\" maxlength=\"4\" value=\"" . $times['year'] . "\">" .
	"&nbsp;&nbsp;" .
	"<input type=\"text\" name=\"ST_hour\"   size=\"4\" maxlength=\"2\" value=\"" . $times['hour'] . "\">:" .
	"<input type=\"text\" name=\"ST_minute\" size=\"4\" maxlength=\"2\" value=\"00\">";

	$orNow = "<input type=\"checkbox\" name=\"startnow\" value=\"true\" checked=\"checked\" > start now";
	
	$or = " - or - ";
	
	$table->addCol($orNow . $or . $timefield);
	$table->addRow();
	$table->addCol("format: day.month.year hour:minute", array (
		"align" => "right",
		"colspan" => "2"
	));
	
	$table->addRow();
	$table->addCol("Do ship types matter?:");
	$table->addCol("<input type=\"checkbox\" name=\"weCareAboutShip\" >Tick box if you want to apply ship payout percentages to this op.");
	
	
	// Now we need the sum of all ores. 
	//$totalOres = count($ORENAMES);

	/*
	// And the sum of all ENABLED ores.
	$totalEnabledOres = $DB->getCol("select count(name) as active from config where name LIKE '%".$OPTYPE."Enabled' AND value='1'");
	$totalEnabledOres = $totalEnabledOres[0];
	*/

	/*
	 * This is evil. We have to create an array that we fill up sorted.
	 * It aint cheap. First, we loop through all the ore values.
	 *//*
	for ($p = 0; $p < $totalOres; $p++) {
		// Then we check each ore if it is enabled.
		$ORE = $DBORE[$ORENAMES[$p]];
		if (getOreSettings($ORE,$OPTYPE)) {
			// If the ore is enabled, add it to the array.
			$left[] = $ORE;
		} else {
			// add to disabled-array.
			$disabledOres[] = $ORE;
		}
	}
	
	$totalEnabledOres = count($left);
	
	// No ores enabled?
	if ($totalEnabledOres == 0) {
		makeNotice("Your CEO has disabled *all* the Oretypes. Please ask your CEO to reactivate at leat one Oretype.", "error", "No valid Oretypes!");
	}

	// The table is, rounded up, exactly half the size of all enabled ores.
	$tableLength = ceil($totalEnabledOres / 2);
	// Now, copy the lower second half into a new array.
	$right = array_slice($left, $tableLength);
	*/
	/*
	 * So now we have an array of all the enabled ores. All we
	 * need to do now, is create a nice, handsome table of it.
	 * Loop through this array.
	 *//*
	for ($i = 0; $i < $tableLength; $i++) {

		// Fetch the right image for the ore.
		$ri_words = str_word_count(array_search($left[$i], $DBORE), 1);
		$ri_max = count($ri_words);
		$ri = strtolower($ri_words[$ri_max -1]);

		// Add a row.
		$table->addRow();

		// left side.
		$table->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . array_search($left[$i], $DBORE) . ".png\"> <input type=\"text\" name=\"$left[$i]\" size=\"10\" value=\"0\"> " . array_search($left[$i], $DBORE) . " wanted. ");

		// We need an ore type (just in case of odd ore numbers)
		if ($right[$i] != "") {
			// right side.

			// Fetch the right image for the ore.
			$ri_words = str_word_count(array_search($right[$i], $DBORE), 1);
			$ri_max = count($ri_words);
			$ri = strtolower($ri_words[$ri_max -1]);

			// Add the column.
			$table->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . array_search($right[$i], $DBORE) . ".png\"> <input type=\"text\" name=\"$right[$i]\" size=\"10\" value=\"0\"> " . array_search($right[$i], $DBORE) . " wanted. ");

		} else {
			// We have an odd number of ores: add empty cell.
			$table->addCol("");
		}

	}

	// Display the ore-disables-disclaimer. (Only if there are disabled oretypes.)
	if (!empty ($disabled)) {
		$disabledText = "The following Oretypes has been disabled by the CEO: $disabled";
	}
	*/
	$submitbutton = "<input type=\"hidden\" name=\"check\" value=\"true\">" .
	"<input type=\"hidden\" value=\"addrun\" name=\"action\">" .
	"<input type=\"submit\" value=\"Create new Operation\" name=\"submit\">";

	// El grande submit button!					
	$table->addHeaderCentered($submitbutton);

	/*
	// Show, if any, disabled ore-types.
	if ($disabledText) {
		$table->addRow();
		$table->addCol("<br><br>" . $disabledText . ".", array (
			"colspan" => "2"
		));
	}*/
	
	// Render the table, and return it.
	return ("<h2>Create a new Operation</h2><form action=\"index.php\" method=\"POST\">" . $table->flush() . "</form>");
}
?>
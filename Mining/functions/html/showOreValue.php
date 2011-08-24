<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showOreValue.php,v 1.5 2008/01/02 20:01:32 mining Exp $
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

// Prints the change form to edit the ore values.
function showOreValue() {
	// Get the globals.
	global $ORENAMES;
	global $DBORE;
	global $DB;

	// load the values.
	if (!isset ($_GET[id])) {
		// No ID requested, get latest
		$orevaluesDS = $DB->query("select * from orevalues ORDER BY ID DESC limit 1");
		$isLatest = true;
	} else
		if (!is_numeric($_GET[id]) || $_GET[ID] < 0) {
			// ID Set, but invalid
			makeNotice("Invalid ID given for ore values! Please go back, and try again!", "warning", "Invalid ID");
		} else {
			// VALID id
			$orevaluesDS = $DB->query("select * from orevalues WHERE id='" . sanitize($_GET[id]) . "' limit 1");
		}

	// Check for a winner.
	if ($orevaluesDS->numRows() <= 0) {
		makeNotice("Invalid ID given for ore values! Please go back, and try again!", "warning", "Invalid ID");
	}

	// Check for latest orevalue
	if (!$isLatest) {
		$latest = $DB->query("select * from orevalues ORDER BY ID DESC limit 1");
		$latest = $latest->fetchRow();
		if ($latest[id] == sanitize($_GET[id])) {
			$isLatest = true;
		}
	}

	$orevalues = $orevaluesDS->fetchRow();

	// Create the table.
	if (!$isLatest) {
		$table = new table(8, true);
		$add = "Archived";
		$colspan = 7;
	} else {
		$table = new table(6, true);
		$add = "Current";
		$colspan = 5;
	}

	$table->addHeader(">> $add Ore Quotes (dated: " . date("m.d.y H:i:s", $orevalues[time]) . ", modified by " . ucfirst(idToUsername($orevalues[modifier])) . ")", array (
		"bold" => true
	));

	$table->addRow();
	$table->addCol("Ore Name", array (
		"colspan" => 2,
		"bold" => true
	));
	$table->addCol("Value", array (
		"bold" => true
	));
	if (!$isLatest) {
		$table->addCol("Diff", array (
			"bold" => true
		));
	}
	$table->addCol("Ore Name", array (
		"colspan" => 2,
		"bold" => true
	));
	$table->addCol("Value", array (
		"bold" => true
	));

	if (!$isLatest) {
		$table->addCol("Diff", array (
			"bold" => true
		));
	}

	// How many ores are there in total? Ie, how long has the table to be?
	$tableLength = ceil(count($ORENAMES) / 2) - 1;

	for ($i = 0; $i <= $tableLength; $i++) {

		$table->addRow();
		$ORE = $ORENAMES[$i];

		// Fetch the right image for the ore.
		$ri_words = str_word_count($ORE, 1);
		$ri_max = count($ri_words);
		$ri = strtolower($ri_words[$ri_max -1]);

		// Ore columns for LEFT side.
		$table->addCol("<img width=\"32\" height=\"32\" src=\"./images/ores/" . $ORE . ".png\">");
		$table->addCol($ORE);
		$table->addCol(number_format($orevalues[$DBORE[$ORE] . Worth]) . " ISK");
		if (!$isLatest) {
			$diff = $orevalues[$DBORE[$ORE] . Worth] - $latest[$DBORE[$ORE] . Worth];
			if ($diff > 0) {
				$color = "#00ff00";
			}
			elseif ($diff == 0) {
				$color = "";
			}
			elseif ($diff <= 0) {
				$color = "#ff0000";
			}
			$table->addCol("<font color=\"$color\">$diff</font>");
		}

		// Ore columns for RIGHT side.
		$ORE = $ORENAMES[$i + $tableLength +1];

		// Fetch the right image for the ore.
		$ri_words = str_word_count($ORE, 1);
		$ri_max = count($ri_words);
		$ri = strtolower($ri_words[$ri_max -1]);

		if ($ORE != "") {
			// Ore columns for LEFT side.
			$table->addCol("<img width=\"32\" height=\"32\" src=\"./images/ores/" . $ORE . ".png\">");
			$table->addCol($ORE);
			$table->addCol(number_format($orevalues[$DBORE[$ORE] . Worth]) . " ISK");
			if (!$isLatest) {
				$diff = $orevalues[$DBORE[$ORE] . Worth] - $latest[$DBORE[$ORE] . Worth];
				if ($diff > 0) {
					$color = "#00ff00";
				}
				elseif ($diff == 0) {
					$color = "";
				}
				elseif ($diff <= 0) {
					$color = "#ff0000";
				}
				$table->addCol("<font color=\"$color\">$diff</font>");
			}
		} else {
			$table->addCol("");
			$table->addCol("");
			$table->addCol("");
			$table->addCol("");
			if (!$isLatest) {
				$table->addCol("");
			}
		}
	}
	if (!$isLatest) {
		$table->addRow("#882020");
		$table->addCol("These values are not the current payout values. Click <a href=\"index.php?action=showorevalue\">here</a> to see up-to-date quotes.", array (
			"colspan" => 8
		));
	}

	/*
	 * Create a list of all previous changes.
	 */
	$AllChanges = $DB->query("SELECT time,id FROM orevalues ORDER BY time ASC");

	while ($ds = $AllChanges->fetchRow()) {
		if ($ds[time] > 0) {
			if ($ds[time] == $orevalues[time]) {
				$otherValues .= "[" . date("d.m.y", $ds[time]) . "] ";
			} else {
				$otherValues .= "[<a href=\"index.php?action=showorevalue&id=$ds[id]\">" . date("d.m.y", $ds[time]) . "</a>] ";
			}
		}
	}

	$table->addRow("#060622");
	$table->addCol("Other quotes:");
	$table->addCol($otherValues, array (
		"colspan" => $colspan
	));

	// return the page
	return ("<h2>Ore Quotes</h2>" . $table->flush());
}
?>
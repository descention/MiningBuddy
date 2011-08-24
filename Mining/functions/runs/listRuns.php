<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRuns.php,v 1.59 2008/01/06 14:03:59 mining Exp $
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
* listRuns
* This returns a nice, friendly list of all the runs in the database.
*
*/
function listRuns() {

	/* bgcolor and i are used to alternate the tablerow
	 * background color.
	 */
	$bgc = array (
		"#222222",
		"#333333"
	);
	$bgi = 0;

	// Our database.
	global $DB;
	global $MySelf;
	global $READONLY;
	global $PREFS;
	$sirstate = $PREFS->getPref("sirstate");

	/*
	 * LIST OPEN RUNS
	 */

	// Query it. 
	$results = $DB->query("select * from runs where endtime IS NULL order by id");

	$mode = array (
		"bold" => true
	);
	$table = new table(8, true);
	$table->addHeader(">> Currently active Operations");
	$table->addRow("#060622");
	$table->addCol("Run ID");
	$table->addCol("Supervisor");
	$table->addCol("Starttime");
	$table->addCol("Endtime");
	$table->addCol("Location");
	$table->addCol("Security");
	$table->addCol("Official run");
	$table->addCol("Locked");

	// Now we loop through each returned result.
	while ($row = $results->fetchRow()) {

		// Skip inofficial runs if user does not want to see them.
		if ((!$sirstate && !$row[isOfficial]) && !($MySelf->getID() == $row[supervisor])) {
			continue;
		}

		$table->addRow();
		$table->addCol("<a href=\"index.php?action=show&id=$row[id]\">" . str_pad($row[id], 5, "0", STR_PAD_LEFT) . "</a>");
		
		$table->addCol(makeProfileLink($row[supervisor]));
		$table->addCol(date("d.m.y H:i", $row[starttime]));

		/* This handles the endtime. Prints endtime if it has already
		* ended, or "active" along with an "end run"-link if still open.
		*/
		unset ($tmp);
		if ("$row[endtime]" == "") {
			$tmp = "<b>active</b>";
			// If access level is above or equal 3 give option to close run.
			if ($MySelf->canCloseRun()) {
				$tmp .= " (<a href=\"index.php?action=endrun&id=$row[id]\">close run</a>)";
			}
		} else {
			$tmp = date("d.m.y H:i", $row[endtime]);
		}

		// Add the end-time to the table.
		$table->addCol($tmp);

		// Show the security status
		$System = new solarSystem($row[location]);
		if ($System->valid()) {
			$table->addCol($System->makeFancyLink());
			$table->addCol($System->getSecurity());
		} else {
			$table->addCol(ucfirst($row[location]));
			$table->addCol("?");
		}

		$table->addCol(yesno($row[isOfficial], true));
		$table->addCol(yesno($row[isLocked], true, true));
		$runsExist = true; // We wont print out table if there are no open runs.
	}

	/*
	 *  LIST CLOSED RUNS
	 */

	// Query it. 
	if (is_numeric($_GET[page]) && $_GET[page] > 0) {
		$page = "LIMIT ". ($_GET[page] * 20) . ", 20" ;
	}
	elseif ($_GET[page] == "all") {
		$page = "";
	} else {
		$page = "LIMIT 20";
	}
	$results = $DB->query("SELECT * FROM runs WHERE endtime IS NOT NULL ORDER BY endtime DESC $page");

	// This is the table header.
	$table_closed = new table(10, true);
	$table_closed->addHeader(">> Archived Operations");
	$table_closed->addRow("#060622");
	$table_closed->addCol("Run ID");
	$table_closed->addCol("Supervisor");
	$table_closed->addCol("Starttime");
	$table_closed->addCol("Endtime");
	$table_closed->addCol("Location");
	$table_closed->addCol("Security");
	$table_closed->addCol("Yield");
	$table_closed->addCol("TMEC(tm)");
	$table_closed->addCol("Was official");

	// Offer delete button.
	if ($MySelf->canDeleteRun() && !$READONLY) {
		$table_closed->addCol("Delete", $mode);
	} else {
		$table_closed->addCol("");
	}

	// Now we loop through each returned result.
	while ($row = $results->fetchRow()) {

		// Skip inofficial runs if user does not want to see them.
		if ((!$sirstate && !$row[isOfficial]) && !($MySelf->getID() == $row[supervisor])) {
			continue;
		}

		$table_closed->addRow();
		$table_closed->addCol("<a href=\"index.php?action=show&id=$row[id]\">" . str_pad($row[id], 5, "0", STR_PAD_LEFT) . "</a>");
		
		$table_closed->addCol(makeProfileLink($row[supervisor]));
		$table_closed->addCol(date("d.m.y H:i", $row[starttime]));

		/* This handles the endtime. Prints endtime if it has already
		* ended, or "active" along with an "end run"-link if still open.
		*/
		unset ($tmp);
		if ("$row[endtime]" == "") {
			$tmp = "<b>active</b>";
			// If access level is above or equal 3 give option to close run.
			if ($MySelf->canCloseRun()) {
				$tmp .= " (<a href=\"index.php?action=endrun&id=$row[id]\">close run</a>)";
			}
		} else {
			$tmp = date("d.m.y H:i", $row[endtime]);
		}

		// Add the end-time to the table.
		$table_closed->addCol($tmp);

		// Show the security status
		$System = new solarSystem($row[location]);
		if ($System->valid()) {
			$table_closed->addCol($System->makeFancyLink());
			$table_closed->addCol($System->getSecurity());
		} else {
			$table_closed->addCol(ucfirst($row[location]));
			$table_closed->addCol("?");
		}

		// get the total ores gained.
		$totalIsk = getTotalWorth($row[id]);
		$table_closed->addCol(number_format($totalIsk, 2) . " ISK");

		// Add the TMEC
		if ($row[tmec] == 0) {
			$TMEC = calcTMEC($row[id]);
		} else {
			$TMEC = $row[tmec];
		}
		$table_closed->addCol($TMEC);

		// Add "run is official" bit.
		$table_closed->addCol(yesno($row[isOfficial], true));
		$closedRunsExist = true; // We wont print out table if there are no open runs.

		// Add possible delete run button.
		if ($MySelf->canDeleteRun() && !$READONLY) {
			$table_closed->addCol("<a href=\"index.php?action=deleterun&id=$row[id]\">delete</a>");
		} else {
			$table_closed->addCol("");
		}
	}
	
	// The "show this many ops"-part.
	$count = $DB->getCol("SELECT COUNT(id) FROM runs WHERE endtime > 0");
	$countSteps = floor($count[0] / 20);
	$showMore = "Switch to page >> ";
	for ($i = 1; $i <= $countSteps; $i++) {
		$thisStep = str_pad($i, 2, "0", STR_PAD_LEFT);
		$showMore .= "[<a href=\"index.php?action=list&page=".$thisStep."\">".$thisStep."</a>] ";	
	}
	$showMore .= "[<a href=\"index.php?action=list&page=all\">All</a>] ";
	$table_closed->addHeader($showMore);

	// Fancy it up!
	$page = "<h2>Mining Operations</h2>";

	// Print the open runs table, IF there are open runs.
	if ($runsExist) {
		$page .= $table->flush() . "<br>";
	}

	// Print the closed runs table, IF there are closed runs.
	if ($closedRunsExist) {
		$page .= $table_closed->flush();
	} else {
		$page .= "<i>There are no (closed) mining operations in the database. Is this a fresh installation?</i>";
	}

	return ($page);
}
?>
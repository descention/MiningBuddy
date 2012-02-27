<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/browser.php,v 1.8 2008/01/02 20:01:32 mining Exp $
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
 * This shows the solarystems, constellations etc.
 */

function browser() {

	// Wash the incoming.
	numericCheck(sanitize($_GET[mode]));
	numericCheck(sanitize($_GET[id]));
	$id = $_GET[id];
	$mode = $_GET[mode];
	global $DB;

	// We differ between 0 = system (detailed), 1 = constellation and 2 = region.
	switch ($_GET[mode]) {
		case ("0") :
			$solar = new solarSystem($id);
			$table = $solar->makeInfoTable();

			//Current Runs in System 
			$openRuns = $DB->query("SELECT * FROM runs WHERE location = '" . $solar->getName() . "' AND endtime IS NULL ORDER BY id");

			if ($openRuns->numRows() > 0) {
				
				$openRunsTable = new table(2, true);
				$openRunsTable->addHeader(">> Current runs in " . $solar->getName());

				// We need this for a new table line.
				$newline = true;

				// Loop through all solarsystems.
				while ($openRun = $openRuns->fetchRow()) {

					// If this is a new table row, add one.
					if ($newline) {
						$openRunsTable->addRow();
						$newline = false;
					} else {
						$newline = true;
					}

					// Add the information.
					$openRunsTable->addCol("<a href=\"index.php?action=show&id=".$openRun[id]."\">#" . str_pad($openRun[id], 4, "0", STR_PAD_LEFT."</a>"));
					
				}
				if (!$newline) {
					$openRunsTable->addCol();
				}
				$openRunsStuff = $openRunsTable->flush();				
				
			}
			
			//Past Runs in System 
			$Runs = $DB->query("SELECT * FROM runs WHERE location = '" . $solar->getName() . "' AND endtime > 0 ORDER BY id");

			if ($Runs->numRows() > 0) {
				
				$RunsTable = new table(2, true);
				$RunsTable->addHeader(">> Past runs in " . $solar->getName());

				// We need this for a new table line.
				$newline = true;

				// Loop through all solarsystems.
				while ($Run = $Runs->fetchRow()) {

					// If this is a new table row, add one.
					if ($newline) {
						$RunsTable->addRow();
						$newline = false;
					} else {
						$newline = true;
					}

					// Add the information.
					$RunsTable->addCol("<a href=\"index.php?action=show&id=".$Run[id]."\">#" . str_pad($Run[id], 4, "0", STR_PAD_LEFT."</a>"));
					
				}
				if (!$newline) {
					$RunsTable->addCol();
				}
				
				// Get the total time spent in this System.
				$time = $DB->getCol("SELECT SUM(endtime - starttime) FROM runs WHERE location='".$solar->getName()."'");
				$time = numberToString($time[0]);
				$RunsTable->addHeaderCentered("Time spent in " . $solar->makeFancyLink() . ": " . $time);
				$RunsStuff = $RunsTable->flush();				
				
			}
			
			$solarStuff = $solar->makeConstellationTable();
			break;
			
	}
	return ("<h2>Solar System Information</h2>" . $table . "<br>" . $solarStuff  . "<br>" . $openRunsStuff . "<br>". $RunsStuff);
}
?>
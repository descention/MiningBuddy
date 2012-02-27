<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showHierarchy.php,v 1.7 2008/05/01 15:37:24 mining Exp $
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
 * This page prints a graphical representation of the corps hierarchy.
 */

function showHierarchy() {

	// Globals!
	global $DB;
	
	// Get all sorted ranks. 	
	$Ranks = $DB->query("SELECT DISTINCT name, rankid, rankOrder FROM ranks ORDER by rankOrder ASC");

	while ($rank = $Ranks->fetchRow()) {
		// Get all the users in the current rank.
		$peopleInRank = $DB->query("SELECT DISTINCT username, rank FROM users WHERE rank='$rank[rankid]' AND deleted='0' AND canLogin='1' ORDER BY username");
		// Are there people in this rank?
		if ($peopleInRank->numRows() > 0) {

			// Create a temp. table.
			$table = new table(1, true);
			$table->addHeader(">> " . $rank[name]);

			while ($peep = $peopleInRank->fetchRow()) {
				$table->addRow();
				$table->addCol("<a href=\"index.php?action=profile&id=".usernameToID($peep[username])."\">".ucfirst($peep[username])."</a>");
			}
			
		$html .= $table->flush() . "<br>";
		unset($table);
		}

	}
	$header = "<h2>" . getConfig("sitename") . " - Hierarchy</h2>";
	return ($header . $html);
	
}
?>
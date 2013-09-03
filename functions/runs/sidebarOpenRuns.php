<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/sidebarOpenRuns.php,v 1.18 2008/01/02 20:01:32 mining Exp $
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
 * This function gets all the open runs and prepared html stuff for
 * the left hand menu.
 */
function sidebarOpenRuns() {
	// globals, as always.
	global $DB;
	global $MySelf;
	global $PREFS;
	
	// Dont do this.
	if (!$MySelf->isValid()) {
		return;
	}
	
	// Query the database, return only id and location.
	$result = $DB->query("select id,location,isOfficial,supervisor,optype from runs where endtime is NULL");

	// no open runs.
	if ($result->numRows() == 0) {
		return (false);
	}
	
	if (is_object($PREFS)) {
		$sirstate = $PREFS->getPref("sirstate");
	} else {
		$sirstate = 1;
	}
	
	$links = "";
	// now loop through each result, and create the hyperlink and image.  
	while ($row = $result->fetchRow()) {
		
		// Skip inofficial runs if user does not want to see them.
		if (((!$sirstate && !$row['isOfficial']) && !($MySelf->getID() == $row['supervisor'])) || $row['optype'] == "Shopping") {
			continue;
		}
		
		// we need this so we know wether there were any runs.
		$notempty = true;
		// This creates the links.
		$links .= "<a class=\"menu\" href=\"index.php?action=show&id=$row[id]\">";
		
		$opType = $row['optype']==""?"Standard":$row['optype'];
		// Add this run to the sidebar.
		$links .= "&gt; " . $row['location'] . " (". $opType .")" . "</a>";
		
	}

	// As long as we had at least one result...
	if ($notempty) {
		// which we did, we finish building the menu fragment.
		//		$links = "<br><br><img border=\"0\" src=\"./images/m-runs-in-progress.png\"><br>".$links;
		// and return it.
		return $links;
	} else {
		// If there are no open runs, we just return a <br>.
		return "<br>";
	}

}
?>
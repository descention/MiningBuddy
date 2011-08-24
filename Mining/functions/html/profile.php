<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/profile.php,v 1.25 2008/01/06 20:13:06 mining Exp $
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
 * This generates an "profile" page for a pilot.
 */

function profile() {

	// The usual suspects.
	global $MySelf;
	global $DB;

	// Set the ID.
	$ID = sanitize($_GET[id]);
	numericCheck($_GET[id], 0);

	// Load the profile.
	$profile = new profile($ID);
	$username = ucfirst(idToUsername($ID));

	// Cache our permissions.
	$canSeeUsers = $MySelf->canSeeUsers();

	// Need the api.
	$api = new api($ID);

	// Create table header.
	$table = new table(2, true);
	$table->addHeader(">> About " . $username);

	$table->addRow();
	$table->addCol("Current rank:");
	$table->addCol(getRank($ID));

	$table->addRow();
	$table->addCol("Last login:");
	$lastLog = $DB->getCol("SELECT lastlogin FROM users WHERE id='" . $ID . "' AND deleted='0' LIMIT 1");
	$table->addCol(date("d.m.y. H:i:s", $lastLog[0]));

	$table->addRow();
	$table->addCol("Total logins:");
	$lastLog = $DB->getCol("SELECT COUNT(authkey) FROM auth WHERE user='" . $ID . "' LIMIT 1");
	$table->addCol(number_format($lastLog[0], 0));

	$table->addRow();
	$table->addCol("Valid api-key on file:");
	$table->addCol(yesno($api->valid(), true));

	$table->addRow();
	$table->addCol("Is available for mining:");
	if ($profile->isOwn()) {
		if ($profile->MinerFlag()) {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&mining=false\">set not available</a>]";
		} else {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&mining=true\">set available</a>]";
		}
	}
	$table->addCol(yesno($profile->MinerFlag(), true) . $temp);

	$table->addRow();
	$table->addCol("Is available for hauling:");
	if ($profile->isOwn()) {
		if ($profile->HaulerFlag()) {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&hauling=false\">set not available</a>]";
		} else {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&hauling=true\">set available</a>]";
		}
	}
	$table->addCol(yesno($profile->HaulerFlag(), true) . $temp);

	$table->addRow();
	$table->addCol("Is available for fighting:");
	if ($profile->isOwn()) {
		if ($profile->FighterFlag()) {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&fighting=false\">set not available</a>]";
		} else {
			$temp = " [<a href=\"index.php?action=modprofile&id=" . $ID . "&fighting=true\">set available</a>]";
		}
	}
	$table->addCol(yesno($profile->FighterFlag(), true) . $temp);

	if ($profile->emailVisible() || $profile->isOwn()) {
		if ($profile->isOwn()) {
			if ($profile->emailVisible()) {
				$temp = " (public) [<a href=\"index.php?action=modprofile&id=" . $ID . "&email=hide\">hide from public</a>]";
			} else {
				$temp = " (hidden) [<a href=\"index.php?action=modprofile&id=" . $ID . "&email=show\">make public</a>]";
			}
		}
		$table->addRow();
		$table->addCol("Email address:");
		$email = $DB->getCol("SELECT email FROM users WHERE id='" . $ID . "' LIMIT 1");
		$table->addCol($email[0] . $temp);
	}

	// Statistics.
	$stats = new table(2, true);
	$stats->addHeader(">> Statistical breakdown");

	$miningRunsJoined = $DB->getCol("SELECT COUNT(id) FROM joinups WHERE userid='" . $ID . "'");
	$miningRunsJoined = $miningRunsJoined[0];
	$OpjoinUps = $DB->getCol("SELECT COUNT(id) FROM joinups WHERE userid='" . $ID . "'");
	$OpjoinUps = $OpjoinUps[0];
	$joinUps = $DB->getCol("SELECT COUNT(id) FROM (SELECT * from joinups WHERE userid='" . $ID . "' GROUP BY run) as uJoinups");
	$joinUps = $joinUps[0];
	$haulingRuns = $DB->getCol("SELECT COUNT(id) FROM hauled WHERE hauler='" . $ID . "'");
	$haulingRuns = $haulingRuns[0];
	$timeMining = $DB->getCol("SELECT SUM(parted - joined) FROM joinups WHERE userid='" . $ID . "' AND parted >1");
	$timeMining = $timeMining[0];
	$timesKicked = $DB->getCol("SELECT COUNT(id) FROM joinups WHERE userid='" . $ID . "' AND status='1'");
	$timesKicked = $timesKicked[0];
	$timesRemoved = $DB->getCol("SELECT COUNT(id) FROM joinups WHERE userid='" . $ID . "' AND status='2'");
	$timesRemoved = $timesRemoved[0];
	$timesBanned = $DB->getCol("SELECT COUNT(id) FROM joinups WHERE userid='" . $ID . "' AND status='3'");
	$timesBanned = $timesBanned[0];
	$timesCharity = $DB->getCol("SELECT COUNT(id) FROM (SELECT * from joinups WHERE userid='" . $ID . "' GROUP BY run) as uJoinups WHERE userid='" . $ID . "' AND charity='1'");
	$timesCharity = $timesCharity[0];

	$tmec = $DB->getCol("SELECT AVG(tmec) FROM runs WHERE isOfficial = 1");
	$tmecJoined = $DB->getCol("SELECT AVG(runs.tmec) FROM joinups, runs WHERE joinups.userid='" . $ID . "' AND joinups.run = runs.id AND runs.endtime > 0 AND runs.isOfficial = 1");
	$tmecNotJoined = $DB->getCol("SELECT AVG(runs.tmec) FROM joinups, runs WHERE joinups.userid='" . $ID . "' AND joinups.run <> runs.id AND runs.endtime > 0 AND runs.isOfficial = 1");
	$tmecDiff = $tmecJoined[0] - $tmecNotJoined[0];

	$stats->addRow();
	$stats->addCol("Mining operations joined:");
	if ($miningRunsJoined > 0) {
		$stats->addCol(number_format($joinUps, 0));
	} else {
		$stats->addCol("never joined.");
	}

	$stats->addRow();
	$stats->addCol("Total operations joinups:");
	if ($OpjoinUps > 0) {
		$stats->addCol(number_format($OpjoinUps, 0));
	} else {
		$stats->addCol("never joined.");
	}

	$stats->addRow();
	$stats->addCol("Hauling runs:");
	if ($haulingRuns > 0) {
		$stats->addCol(number_format($haulingRuns, 0));
	} else {
		$stats->addCol("never hauled.");
	}

	$stats->addRow();
	$stats->addCol("Time spent mining:");
	if ($timeMining > 0) {
		$stats->addCol(numberToString($timeMining));
	} else {
		$stats->addCol("never mined.");
	}

	$stats->addRow();
	$stats->addCol("Average TMEC:");
	$stats->addCol(number_format($tmec[0], 3));

	$stats->addRow();
	$stats->addCol("Average TMEC on Ops <b>with</b> " . $username . ":");
	$stats->addCol(number_format($tmecJoined[0], 3));

	$stats->addRow();
	$stats->addCol("Average TMEC on Ops <b>without</b> " . $username . ":");
	$stats->addCol(number_format($tmecNotJoined[0], 3));

	$stats->addRow();
	$stats->addCol("TMEC difference:");
	if ($tmecDiff >= 0) {
		$stats->addCol("<font color=\"#00ff00\">" . number_format($tmecDiff, 3), true . "</font>");
	} else {
		$stats->addCol("<font color=\"#ff0000\">" . number_format($tmecDiff, 3), true . "</font>");
	}

	$stats->addRow();
	$stats->addCol("Times removed from OP:");
	$stats->addCol(number_format($timesRemoved, 0));

	$stats->addRow();
	$stats->addCol("Times kicked from OP:");
	$stats->addCol(number_format($timesKicked, 0));

	$stats->addRow();
	$stats->addCol("Times banned from OP:");
	$stats->addCol(number_format($timesBanned, 0));

	$stats->addRow();
	$stats->addCol("Times declared charity:");
	$stats->addCol(number_format($timesCharity, 0));

	/*
	 * Assemble the heavy-duty SQL query.
	 * It is dynamic because this way we can easily add ores from 
	 * config-system.php to the system without code rewrite.
	 */
	global $DBORE;
	global $ORENAMES;
	foreach ($DBORE as $ORE) {
		$new = $ORE;
		if ($last) {
			$SQLADD .= "SUM(" . $last . ") AS total" . $last . ", ";
		}
		$last = $new;
	}
	$SQLADD .= "SUM(" . $last . ") AS total" . $last . " ";
	$SQL = "SELECT " . $SQLADD . " FROM hauled WHERE hauler='" . $ID . "'";

	// Now query it.
	$totalOREDB = $DB->query("$SQL");


if (DB::isError($totalOREDB)) {
    /*
     * This is not what you would really want to do in
     * your program.  It merely demonstrates what kinds
     * of data you can get back from error objects.
     */
    echo 'Standard Message: ' . $totalOREDB->getMessage() . "\n";
    echo '\n ';
    echo 'Standard Code: ' . $totalOREDB->getCode() . "\n";
    echo '\n ';
    echo 'DBMS/User Message: ' . $totalOREDB->getUserInfo() . "\n";
    echo '\n ';
    echo 'DBMS/Debug Message: ' . $totalOREDB->getDebugInfo() . "\n";
    echo '\n ';
    exit;
}

	// Create table.
	$totalOre_table = new table(2, true);
	$totalOre_table->addHeader(">> Total ore hauled");

	// Loop through the result (single result!)
	if ($totalOREDB->numRows() > 0) {
		while ($totalORE = $totalOREDB->fetchRow()) {
			// Now check each ore type.
			foreach ($ORENAMES as $ORE) {
				// And ignore never-hauled ore
				if ($totalORE[total . $ORE] > 0) {
					// We got some ore!
					$totalOre_table->addRow();
					$totalOre_table->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . $ORE . ".png\">Total " . $ORE . " hauled:");
					$totalOre_table->addCol(number_format($totalORE[total . $ORE]));
					$gotOre = true;
				}
			}
		}
		if ($gotOre) {
			$oretable_r = "<br>" . $totalOre_table->flush();
		}
	}

	// Image thingy.

	// We serve small images IGB.
	global $IGB;
	global $IGB_VISUAL;
	if ($IGB && $IGB_VISUAL) {
		$image = "<img src=\"portrait:".$api->getCharacterID()."\" SIZE=\"128\">";
	} else {
		$image = $profile->getImage("large");		
	}
	$picTable = new table(true, 1);
	$picTable->addHeader(">> Picture of " . $username);
	$picTable->addRow();
	$picTable->addCol($image);
	$imageTable = $picTable->flush();

	// About
	if ($profile->GetAbout() && !$profile->isOwn()) {
		$aboutTable = new table(1, true);
		$aboutTable->addHeader(">> " . $username . " says...");
		$aboutTable->addRow();
		$aboutTable->addCol(nl2br($profile->GetAbout()));
		$aboutTable = "<br>" . $aboutTable->flush();
	}

	if ($profile->isOwn()) {
		$aboutTable = new table(1, true);
		$aboutTable->addHeader(">> Enter a public viewable text here");
		$aboutTable->addRow();
		$form = "<form action=\"index.php\" method=\"POST\">";
		$form .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
		$form .= "<input type=\"hidden\" name=\"action\" value=\"modprofile\">";
		$form .= "<input type=\"hidden\" name=\"id\" value=\"" . $ID . "\">";
		$aboutTable->addCol("<textarea rows=\"18\" cols=\"80\" name=\"about\">" . $profile->GetAbout() . "</textarea>");
		$aboutTable->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update about\">");
		$aboutTable = "<br>" . $form . $aboutTable->flush() . "</form>";
	}

	// quick "jump to" -thingy.
	$peeps = $DB->query("SELECT DISTINCT username,id FROM users WHERE deleted = 0 AND canLogin = 1 ORDER BY username ASC");
	if ($peeps->numRows() > 0) {
		while ($p = $peeps->fetchRow()) {
			if ($ID == $p[id]) {
				$pdm .= "<option SELECTED value=\"" . $p[id] . "\">" . ucfirst($p[username]) . " (current)</option>";
			} else {
				$pdm .= "<option value=\"" . $p[id] . "\">" . ucfirst($p[username]) . "</option>";
			}
		}
		$pdm = "<select name=\"id\">" . $pdm . "</select></form>";
		$quickChooser = new table(1, true);
		$quickChooser->addHeader(">> Quick jump to profile");
		$quickChooser->addRow();
		$quickChooser->addCol($pdm);
		$quickChooser->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Switch\">");
		$quickChooser = "<form action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"action\" value=\"profile\">" . $quickChooser->flush() . "</form>";
	}

	$page = "<h2>View profile</h2>" . $quickChooser . $imageTable . "<br>" . $table->flush() . "<br>" . $stats->flush() . $oretable_r . $aboutTable;
	return ($page);

}
?>
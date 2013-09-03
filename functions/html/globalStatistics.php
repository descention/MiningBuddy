<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/globalStatistics.php,v 1.51 2008/01/06 19:41:51 mining Exp $
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

function globalStatistics() {

	// We need some stuff.
	global $DB;
	global $MySelf;

	/*
	 * Global Information
	 */

	// Create the header of the table
	$stat_table = new table(2, true);
	$stat_table->addHeader(">> Global Information for " . getConfig("sitename"));

	// Total Users
	$temp = $DB->getCol("SELECT COUNT(id) FROM users WHERE deleted='0'");
	$totalUsers = $temp[0];
	$stat_table->addRow();
	$stat_table->addCol("Total user accounts:");
	$stat_table->addCol(number_format($totalUsers, 0));

	// Total Logins
	$temp = $DB->getCol("SELECT COUNT(authkey) FROM auth");
	$temp = $temp[0];
	$stat_table->addRow();
	$stat_table->addCol("Total logins:");
	$stat_table->addCol(number_format($temp, 0));

	// Total failed logins
	$temp = $DB->getCol("SELECT COUNT(incident) FROM failed_logins");
	$temp = $temp[0];
	$stat_table->addRow();
	$stat_table->addCol("Total failed logins:");
	$stat_table->addCol(number_format($temp, 0));

	// Total API keys
	$temp = $DB->getCol("SELECT COUNT(userid) FROM api_keys");
	$totalApiKeys = $temp[0];
	if ($totalApiKeys > 0) {
		$stat_table->addRow();
		$stat_table->addCol("Total API keys stored:");
		$stat_table->addCol(number_format($totalApiKeys, 0));

		// Total API keys
		$temp = $DB->getCol("SELECT COUNT(userid) FROM api_keys WHERE api_valid=1");
		$totalValidApiKeys = $temp[0];
		$stat_table->addRow();
		$stat_table->addCol("Total API keys validated:");
		$stat_table->addCol(number_format($totalValidApiKeys, 0));

		// Total API keys percentage
		$stat_table->addRow();
		$stat_table->addCol("Percent of stored keys valid:");
		$stat_table->addCol(number_format((($totalValidApiKeys * 100) / $totalApiKeys), 2) . "%");

		// Total API keys percentage (over all users)
		$stat_table->addRow();
		$stat_table->addCol("Percent of pilots submited API keys:");
		$stat_table->addCol(number_format((($totalApiKeys * 100) / $totalUsers), 2) . "%");
	}

	/*
	 * Mining Information
	 */

	// Create the header of the table
	$mining_table = new table(2, true);
	$mining_table->addHeader(">> Mining Information for " . getConfig("sitename"));

	// Total Mining Operations
	$temp = $DB->getCol("SELECT COUNT(id) FROM runs WHERE isOfficial = 1");
	$totalMiningOps = $temp[0];
	$mining_table->addRow();
	$mining_table->addCol("Total Mining Operations:");
	$mining_table->addCol(number_format($totalMiningOps, 0));

	// Total Number of Joins
	$temp = $DB->getCol("SELECT COUNT( uJoinups ) FROM (SELECT COUNT( id ) AS uJoinups FROM joinups GROUP BY `run`,`userid`) AS suJoinups");
	$totalJoinUps = $temp[0];
	$mining_table->addRow();
	$mining_table->addCol("Total joinups:");
	$mining_table->addCol(number_format($totalJoinUps, 0));

	// Total Hauling Runs
	$temp = $DB->getCol("SELECT COUNT(id) FROM hauled");
	$totalHaulingRuns = $temp[0];
	$mining_table->addRow();
	$mining_table->addCol("Total Hauling Runs:");
	$mining_table->addCol(number_format($totalHaulingRuns, 0));

	// Total ISK Mined
	$mining_table->addRow();
	$mining_table->addCol("Total ISK mined:");
	$totalIskMined = calculateTotalIskMined();
	$mining_table->addCol(number_format($totalIskMined) . " ISK");

	// Average TMEC
	$aTMEC = $DB->getCol("SELECT AVG(tmec) FROM runs WHERE isOfficial = 1");
	$aTMEC = $aTMEC[0];
	$mining_table->addRow();
	$mining_table->addCol("Average TMEC:");
	if ($aTMEC <= 0) {
		$aTMEC = 0;
	}
	$mining_table->addCol(number_format($aTMEC, 3));
	
	// Total time spent mining
	$temp = $DB->getCol("SELECT SUM(endtime-starttime) AS time FROM runs WHERE endtime >0 AND isOfficial = 1");
	$time = $temp[0];

	if ($time > 0) {
		$totalTimeSpentMining = $time;
		$string = numberToString($time);
	} else {
		$string = "Never mined at all!";
	}
	$mining_table->addRow();
	$mining_table->addCol("Total time spent mining:");
	$mining_table->addCol($string);

	// Total pilot time
	$time = $DB->getCol("select SUM(parted-joined) as time from joinups WHERE parted >0");
	$time = $time[0];
	$mining_table->addRow();
	$mining_table->addCol("Total time combined from all pilots:");
	if ($time > 0) {
		$totalPilotTime = $time;
		$string = numberToString($time);
	} else {
		$string = "Never mined at all!";
	}
	$mining_table->addCol($string);

	/*
	 * Money Stuff
	 */
	$trans_Count = $DB->getCol("SELECT COUNT(id) FROM transactions");
	$trans_Count = $trans_Count[0];
	if ($trans_Count > 0) {
		$trans = new table(2, true);
		$trans->addHeader(">> Financial Statistics");

		$trans->addRow();
		$trans->addCol("Total Transactions made:");
		$trans->addCol(number_format($trans_Count, 0));

		$tmw = $DB->getCol("SELECT SUM(amount) FROM transactions WHERE type ='1'");
		$tmd = $DB->getCol("SELECT SUM(amount) FROM transactions WHERE type ='0'");
		$tmw = $tmw[0];
		$tmd = $tmd[0];
		$trans->addRow();
		$trans->addCol("Total Money withdrawn:");
		$trans->addCol(number_format($tmw * -1, 2) . " ISK");
		$trans->addRow();
		$trans->addCol("Total Money deposited:");
		$trans->addCol(number_format($tmd, 2) . " ISK");
		$trans->addRow();
		$trans->addCol("Difference:");
		$trans->addCol(number_format($tmd + $tmw, 2) . " ISK");

		/*
		 * Abbreviations:
		 * por - PayOutRequests
		 * pord - PayOutRequests Done
		 * port - PayOutRequests Total
		 * portd - PayOutRequests Total Done
		 */
		$por = $DB->getCol("SELECT COUNT(request) FROM payoutRequests");
		$port = $DB->getCol("SELECT SUM(amount) FROM payoutRequests");
		$portd = $DB->getCol("SELECT SUM(amount) FROM payoutRequests WHERE payoutTime is NULL");
		$pord = $DB->getCol("SELECT COUNT(request) FROM payoutRequests WHERE payoutTime is NULL");
		$por = $por[0];
		$pord = $pord[0];
		$port = $port[0];
		$portd = $portd[0];

		$trans->addRow();
		$trans->addCol("Total payout requests:");
		$trans->addCol(number_format($por, 0));

		$trans->addRow();
		$trans->addCol("Payout requests fullfilled:");
		$trans->addCol(number_format(($por - $pord), 0));

		$trans->addRow();
		$trans->addCol("Payout requests pending:");
		$trans->addCol(number_format($pord, 0));

		$trans->addRow();
		$trans->addCol("Total payout requested:");
		$trans->addCol(number_format($port, 2) . " ISK");

		$trans->addRow();
		$trans->addCol("Total requested paid:");
		$trans->addCol(number_format(($port - $portd), 2) . " ISK");

		$trans->addRow();
		$trans->addCol("Total requested open:");
		$trans->addCol(number_format($portd, 2) . " ISK");

		$trans->addHeader("A positive difference means the Corp owes the players, a negative difference means the player owes the Corp.");

		$trans_r = "<br>" . $trans->flush();
	}

	/*
	 * Mining Statistics
	 */

	// Create the header of the table
	$miningStats_table = new table(2, true);
	$miningStats_table->addHeader(">> Mining Statistics for " . getConfig("sitename"));

	// Average ISK / OP
	$miningStats_table->addRow();
	$miningStats_table->addCol("Average ISK per Op:");
	$miningStats_table->addCol(number_format(($totalIskMined / $totalMiningOps), 2) . " ISK");

	// Average ISK/ Hour
	$miningStats_table->addRow();
	$miningStats_table->addCol("Average ISK per hour:");
	$miningStats_table->addCol(number_format(($totalIskMined / (ceil($totalTimeSpentMining / 3600))), 2) . " ISK");

	// Average joinups / Op
	$miningStats_table->addRow();
	$miningStats_table->addCol("Average Joinups per Op:");
	$miningStats_table->addCol(number_format(($totalJoinUps / $totalMiningOps), 2));

	// Average hauls per OP:
	$miningStats_table->addRow();
	$miningStats_table->addCol("Average hauls per Op:");
	$miningStats_table->addCol(number_format(($totalHaulingRuns / $totalMiningOps), 2));

	/*
	 * Hauler statistics
	 */
	$haulers = $DB->query("SELECT DISTINCT hauler, COUNT(miningrun) AS runs FROM hauled GROUP BY hauler ORDER BY runs DESC LIMIT 15");
	if ($haulers->numRows() > 0) {
		
		$hauler_stats = new table(2, true);
		$hauler_stats->addHeader("Most hauling trips");
		
		while ($h = $haulers->fetchRow()) {
			
			// place counter.
			$place++;

			$hauler_stats->addRow();
			$hauler_stats->addCol("Place #".$place.":");
			$hauler_stats->addCol(makeProfileLink($h[hauler]) . " with " . number_format($h[runs]) . " runs!");
		}
		$hauler_stats_table = "<br>" . $hauler_stats->flush();	
	}
		
	/*
	 * Most frequent joiners
	 */

    $MFJDB = $DB->query("SELECT COUNT(userid) AS count, userid FROM (SELECT * FROM joinups GROUP BY userid,run) AS ujoinups GROUP BY userid ORDER BY count DESC LIMIT 15");
	
	if ($MFJDB->numRows() > 0) {
		// Create the header of the table
		$frequentJoiners_table = new table(2, true);
		$frequentJoiners_table->addHeader(">> Most frequent joiners for " . getConfig("sitename"));

		$place = "1";
		while ($FJ = $MFJDB->fetchRow()) {
			$frequentJoiners_table->addRow();
			$frequentJoiners_table->addCol("Place #" . $place . ":");
			$frequentJoiners_table->addCol(makeProfileLink($FJ[userid]) . " with " . $FJ[count] . " joinups!");
			$place++;
		}
		$MFJ_r = "<br>" . $frequentJoiners_table->flush();
	}

	/*
	 * Pilot record with mining time
	 */
	$PMT = $DB->query("select SUM(parted-joined) AS totaltime, userid from joinups WHERE parted >0 GROUP BY userid ORDER BY totaltime DESC LIMIT 15");

	if ($PMT->numRows() > 0) {
		// Create the header of the table
		$mostOnline_table = new table(2, true);
		$mostOnline_table->addHeader(">> Most time spent mining");

		$place = 1;
		while ($P = $PMT->fetchRow()) {
			$time = $P[totaltime];
			if ($time > 0) {
				$string = numberToString($time);

				$mostOnline_table->addRow();
				$mostOnline_table->addCol("Place #" . $place . ":");
			    $mostOnline_table->addCol(makeProfileLink($P[userid]) . " with " . $string);
				
				$place++;
			}
		}
		$MO_r = "<br>" . $mostOnline_table->flush();
	}

	/*
	 * Longest OPS
	 */

	$LOPS = $DB->query("select SUM(endtime-starttime) AS totaltime, id, location FROM runs WHERE endtime > 0 AND isOfficial = 1 GROUP BY id ORDER BY totaltime DESC LIMIT 15");
	if ($LOPS->numRows() > 0) {
		// Create the header of the table
		$lops_table = new table(2, true);
		$lops_table->addHeader(">> Longest Ops for " . getConfig("SITENAME"));

		$place = 1;
		while ($OP = $LOPS->fetchRow()) {
			$time = $OP[totaltime];

			if ($time > 0) {
				$string = numberToString($time);

				// Make system clickable.
				$system = new solarSystem($OP[location]);
				$loc = $system->makeFancyLink();

				$lops_table->addRow();
				$lops_table->addCol("Place #" . $place . ": Operation <a href=\"index.php?action=show&id=" . $OP[id] . "\">#" . str_pad($OP[id], 4, "0", STR_PAD_LEFT) . "</a> in " . $loc . ":");
				$lops_table->addCol($string);
				$place++;
			}
		}
		$LOPS_r = "<br>" . $lops_table->flush();
	}

	/*
	 * Highest TMEC runs
	 */

	// Load the top runs out of the database.
	$TMECDB = $DB->query("SELECT * FROM runs WHERE isOfficial = 1 AND endtime > 0 ORDER BY tmec DESC LIMIT 15");

	// Check that we have any!
	if ($TMECDB->numRows() > 0) {

		// Create table header for tmec.
		$TMEC = new table(3, true);
		$TMEC->addHeader(">> Highest rated TMEC Ops");

		// Reset first place again.
		$place = 1;

		// Now loop through the winners.
		while ($r = $TMECDB->fetchRow()) {

			// Calculate TMEC
			$thisTMEC = calcTMEC($r[id]);

			// This this is TMEC is zero or below.
			if ($thisTMEC <= 0) {
				break;
			}

			// If TMEC > 0, add it.
			$TMEC->addRow();

			// Load the solarsystem its in.
			$system = new solarSystem($r[location]);
			$location = $system->makeFancyLink();

			// Add tmec stuff.
			$TMEC->addCol("Place #" . $place . ":");
			$TMEC->addCol("Op #<a href=\"index.php?action=show&id=" . $r[id] . "\">" . str_pad($r[id], 4, "0", STR_PAD_LEFT) . "</a> in " . $location);
			$TMEC->addCol("Scored a TMEC of " . $thisTMEC . "!");

			// Increase place by one.
			$place++;
		}

		// Render the table.
		$TMEC_r = "<br>" . $TMEC->flush();
	}

	/* 
	 * Total mined ore
	 */

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
			$SQLADD .= "(select coalesce(SUM(Quantity),0) from hauled where Item = '" . $last . "') AS total" . $last . ", ";
		}
		$last = $new;
	}
	$SQLADD .= "(select coalesce(SUM(Quantity),0) from hauled where Item = '" . $last . "') AS total" . $last . " ";
	$SQL = "SELECT " . $SQLADD ;
	//$SQL = "select Item, coalesce(SUM(Quantity),0) as total from hauled group by Item";
	
	
	// Now query it.
	$totalOREDB = $DB->query("$SQL");

	// Create table.
	$totalOre_table = new table(2, true);
	$totalOre_table->addHeader(">> Total ore mined for " . getConfig("SITENAME"));

	// Loop through the result (single result!)
	if ($totalOREDB->numRows() > 0) {
		echo "<!-- Got rows for ore stats -->";
		while ($totalORE = $totalOREDB->fetchRow()) {
			// Now check each ore type.
			foreach ($ORENAMES as $ORE) {
				// And ignore never-hauled ore
				if ($totalORE[total . $DBORE[$ORE]] > 0) {
					// We got some ore!
					$totalOre_table->addRow();
					$totalOre_table->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . $ORE . ".png\">Total " . $ORE . ":");
					$totalOre_table->addCol(number_format($totalORE[total . $DBORE[$ORE]]));
					$gotOre = true;
				}
			}
		}
		if ($gotOre) {
			$oretable_r = "<br>" . $totalOre_table->flush();
		}
	}
	
	/*
	 * Assemble the heavy-duty SQL query.
	 * It is dynamic because this way we can easily add ships from 
	 * config-system.php to the system without code rewrite.
	 */
	global $DBSHIP;
	global $SHIPNAMES;
	foreach ($DBSHIP as $SHIP) {
		$new = $SHIP;
}
	/*
	 * Most beloved Systems
	 */

	$MBS = $DB->query("select SUM(endtime-starttime) as timespent, location FROM runs WHERE endtime > 0 AND isOfficial = 1 GROUP BY location ORDER BY timespent DESC LIMIT 10");
	if ($MBS->numRows() > 0) {
		$MBST = new table(2, true);
		$MBST->addHeader(">> Most loved locations");

		while ($LOC = $MBS->fetchRow()) {
			if ($LOC[timespent] > 0) {
				$MBST->addRow();
				$system = new solarSystem($LOC[location]);
				$MBST->addCol($system->makeFancyLink());

				$MBST->addCol(numberToString($LOC[timespent]));
			}
		}
		$MBST_r = "<br>" . $MBST->flush();
	}
	
	/*
	* Most charitable folks
	*/
	$charity = $DB->query("SELECT users.username, COUNT(uJoinups.charity) as NOBLE FROM (SELECT * FROM joinups GROUP BY userid,run) as uJoinups, users WHERE users.id = uJoinups.userid AND uJoinups.charity=1 GROUP BY users.username ORDER BY NOBLE DESC, username ASC LIMIT 15");

	 if ($charity->numRows() > 0){
	 	$charity_table = new table(2, true);
	 	$charity_table->addHeader(">> Most charitable pilots");
	 	unset($j);
	 	while ($c = $charity->fetchRow()) {
	 		$j++;
	 		$charity_table->addRow();
			$charity_table->addCol("Place #".$j.":");
			$charity_table->addCol(makeProfileLink(usernameToID($c[username])) . " with " . $c[NOBLE] . " charitable acts!");
			$charityCount = $charityCount + $c[NOBLE];	 		
	 	}
	 	$charity_table->addHeader("A total of $charityCount charitable actions have been recorded.");
	 	$charity_table = "<br>" . $charity_table->flush();
	 }

	$page = "<h2>Global statistics</h2>" . $stat_table->flush() .
	$trans_r . "<br>" .
	$mining_table->flush() . "<br>" .
	$miningStats_table->flush() .
	$hauler_stats_table .
	$MFJ_r .
	$MO_r .
	$charity_table.
	$LOPS_r .
	$TMEC_r .
	$oretable_r .
	$MBST_r;
	return ($page);

}
?>
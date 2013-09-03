<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/showRanks.php,v 1.5 2008/01/06 19:41:51 mining Exp $
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
  * This prints a html page with all the current ranks, and the option to
  * add new ones.
  */
 
 function showRanks() {
 	
 	// We needeth the databaseth!
	global $DB;
	global $MySelf;
	 
	// Is sire alloweth to logineth?
	if (!$MySelf->canEditRank()) {
		makeNotice("You do not have sufficient rights to access this page.", "warning", "Access denied");
	}
	
	// Get all current ranks.
	$ranks_ds = $DB->query("SELECT * FROM ranks ORDER BY rankOrder ASC");
	$currentRanks = $ranks_ds->numRows();
	
	// Are there any ranks defined yet?
	if ($currentRanks > 0) {
		// Yuh. Create table.
		$headerConfig = array("bold"=>true, "align"=>"center");
		$table = new table(4, true);
		$table->addHeader(">> Edit current ranks");
		$table->addRow();
		$table->addCol("Rank Order", $headerConfig);
		$table->addCol("Rank Name", $headerConfig);
		$table->addCol("Nr. of times Issued", $headerConfig);
		$table->addCol("Delete Rank", $headerConfig);

		// Create a nice, fancy row for every rank.		
		while ($rank = $ranks_ds->fetchRow()) {
			$table->addRow();
			
			for ($i=1; $i<= $currentRanks; $i++){
				$ro = str_pad($i, 3, "0", STR_PAD_LEFT);
				if ($rank[rankOrder] == $i) {
					$pdm .= "<option SELECTED value=\"$ro\">$i</option>";
				}else{
					$pdm .= "<option value=\"$ro\">$i</option>";
				}
			}
			$ddm = "<select name=\"order_".$rank[rankid]."\">" . $pdm . "</select>";
			$table->addCol($ddm, $headerConfig);
			$table->addCol("<input type=\"text\" name=\"title_".$rank[rankid]."_name\" value=\"".$rank[name]."\">", $headerConfig);
			
			// how many times has the rank been issue?
			$count = $DB->getCol("SELECT COUNT(id) FROM users WHERE rank='$rank[rankid]' AND deleted='0'");
			$count = $count[0];
			if ($count < 1) {
				$table->addCol("<i>Rank not used</i>");
			} else {
				$table->addCol($count);
			}
			
			$table->addCol("<a href=\"index.php?action=deleterank&id=$rank[rankid]\">delete</a>", $headerConfig);
			unset($pdm);
			unset($ddm);
		}		
		
		// Submit button & stuff.
		$hidden = "<input type=\"hidden\" name=\"check\"  value=\"true\">"."<input type=\"hidden\" name=\"action\"  value=\"editranks\">";
		$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update Ranks\">");
		$rankTable = "<form action=\"index.php\" method=\"POST\">" . $table->flush() . $hidden . "</form>";
		unset($table);
		unset($currentRanks);
	}
	
	// Create the new-rank-form-jiggamajig.
	$table = new table(2, true);
	$table->addHeader(">> Add a new rank");
	$table->addRow();
	$table->addCol("Rank name:");
	$table->addCol("<input type=\"text\" name=\"rankname\">");
	
	$hidden = "<input type=\"hidden\" name=\"check\"  value=\"true\">"."<input type=\"hidden\" name=\"action\"  value=\"addnewrank\">";
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Add Rank\">");
	$addRankTable = "<form action=\"index.php\" method=\"POST\">" . $table->flush() . $hidden . "</form>";
	
	// Flush the page!
	return ("<h2>Edit the ranks</h2>" . $rankTable . $addRankTable);
	
 }
 
 ?>
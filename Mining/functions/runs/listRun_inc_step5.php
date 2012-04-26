<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step5.php,v 1.13 2008/01/02 20:01:32 mining Exp $
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

$ressources_info = new table(7, true);
$ressources_info->addHeader(">> Resources Information");
$ressources_info->addRow("#080822");
$ressources_info->addCol("Item", array (
	"bold" => true
));
$ressources_info->addCol("", array (
	"bold" => true
));
$ressources_info->addCol("Quantity / m3", array (
	"bold" => true
));
$ressources_info->addCol("Wanted / m3", array (
	"bold" => true
));
$ressources_info->addCol("Remaining / m3", array (
	"bold" => true
));
$ressources_info->addCol("Value", array (
	"bold" => true
));
$ressources_info->addCol("ISK", array (
	"bold" => true,
	"align" => "right"
));

// Load current payout values.
while ($mval = $mvalues->fetchrow()) {
	// Voila, le scary monster!
	//$oval = $ovalues->fetchRow() AND 
	$oval = $ovalues;
	$r = $DB->query("select item, sum(Quantity) as total, typeName as name, volume, typeID from hauled, ".$STATIC_DB.".invTypes where item = replace(replace(typeName,' ',''),'-','') and miningrun = '$ID' group by item having sum(Quantity) <> 0");
	while($r2 = $r->fetchRow()){
		$ORE = $r2['item'];
		// We need a Variable name with the word Wanted and M3 (for the wanted and m3 columns)
		$OREWANTED = $ORE . "Wanted";
		//Pulls the m3 of each ore type.
		$OREWORTH = getMarketPrice($r2['typeID']);
		$OREM3 = $r2['volume'];
		
		/* If an ore is neither wanted nor has been harvested so far, we dont print
		 * that row to save precious in game browser space.
		 */

		if (($row[$ORE] != 0) || ($row[$OREWANTED] >= 1)) {

			/* This is actually the main table. It prints the associated array
			 * lists into a neat human readable output.
			 */
			 
			// Calculates the Worth of this ore.
			$worth = ($OREWORTH * $row[$ORE]);
			$totalworth = $totalworth + $worth;

			//Do Not Make any changes, It's finally working!			
			if ($row[$ORE] == 0) {
				$tmp_ore = "<i>none</i>";
				$tmp_ore_m3 = "<i>none</i>";
			} else {
				$tmp_ore = number_format($row[$ORE]);
				$tmp_ore_m3 = number_format($OREM3 * abs($row[$ORE]),2) . " m3";
				$total_ore_m3 = $total_ore_m3 + ($OREM3 * abs($row[$ORE]));
			}

			if ($row[$OREWANTED] == 0) {				
				$tmp_ore_wanted = "<i>none</i>";
				$tmp_ore_wanted_m3 = "<i>none</i>";
				$ore_remaining = "<i>none</i>";
				$ore_remaining_m3 = "<i>none</i>";
			} else {
				$tmp_ore_wanted = number_format($row[$OREWANTED]);
				$tmp_ore_wanted_m3 = number_format($OREM3 * $row[$OREWANTED],2) . " m3";
				$total_ore_wanted_m3 = $total_ore_wanted_m3 + ($OREM3 * $row[$OREWANTED]);
				$tmp_ore_remaining = number_format($row[$OREWANTED] - $row[$ORE]);
				if ($tmp_ore_remaining <= 0) {
					$ore_remaining = "<i>none</i>";
					$ore_remaining_m3 = "<i>none</i>";
				} else {
					$ore_remaining = NoNeg(number_format($row[$OREWANTED] - $row[$ORE]));
					$ore_remaining_m3 = NoNeg(number_format($OREM3 * ($row[$OREWANTED] - $row[$ORE]),2)) . " m3";
					$total_ore_remaining_m3 = $total_ore_remaining_m3 + ($OREM3 * ($row[$OREWANTED] - $row[$ORE]));
				}	
			}			
			$ressources_info->addRow();

			// Fetch the right image for the ore.
			$ri_words = str_word_count($r2['name'], 1);
			$ri_max = count($ri_words);
			$ri = strtolower($ri_words[$ri_max -1]);

			$ressources_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/ores/" . $r2['name'] . ".png\">", array (
				"width" => "64"
			));
			$ressources_info->addCol($r2['name'], array (
				"bold" => true
			));
			
			$ressources_info->addCol($tmp_ore . " / " . $tmp_ore_m3);
			$ressources_info->addCol($tmp_ore_wanted . " / " . $tmp_ore_wanted_m3);
			$ressources_info->addCol($ore_remaining . " / " . $ore_remaining_m3);
			$ressources_info->addCol(number_format($OREWORTH) . " ISK");
			$ressources_info->addCol(number_format($worth, 2) . " ISK", array (
				"bold" => true,
				"align" => "right"
			));

			$gotOre = true; // We set this so we know we have SOME ore.
		}
	}
}

$ressources_info->addRow("#060622");
$ressources_info->addCol("");
$ressources_info->addCol("Total m3:", array (
	"bold" => true,
	"align" => "left",
));
//mined ore in m3
$ressources_info->addCol(number_format($total_ore_m3,2) . " m3", array (
	"align" => "left",
	"bold" => true
));
//wanted ore in m3
$ressources_info->addCol(number_format($total_ore_wanted_m3,2) . " m3 Wanted", array (
	"align" => "left",
	"bold" => true
));
//remaining ore in m3
$ressources_info->addCol(number_format($total_ore_remaining_m3,2) . " m3 Remaining", array (
	"align" => "left",
	"bold" => true
));
$ressources_info->addCol("Gross value:", array (
	"bold" => true,
	"align" => "right",
	"colspan" => 1
));

$ressources_info->addCol(number_format($totalworth, 2) . " ISK", array (
	"align" => "right",
	"bold" => true
));


// Math fun.
$taxes = abs($totalworth * $row['corpkeeps']) / 100;
$net = $totalworth - $taxes;

$ressources_info->addRow("#060622");
$ressources_info->addCol("Corp keeps:", array (
	"bold" => true,
	"align" => "right",
	"colspan" => 6
));
$ressources_info->addCol(number_format($taxes, 2) . " ISK", array (
	"align" => "right",
	"bold" => true
));

$ressources_info->addRow("#060622");
$ressources_info->addCol("Net value:", array (
	"bold" => true,
	"align" => "right",
	"colspan" => 6
));
$ressources_info->addCol(number_format($net, 2) . " ISK", array (
	"align" => "right",
	"bold" => true
));
?>

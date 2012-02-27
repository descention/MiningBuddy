<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step9.php,v 1.13 2008/01/02 20:01:32 mining Exp $
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


 
include ('./functions/runs/MineralCalculations.php');

//Table Information
$conversion_info = new table(8, true);
$conversion_info->addHeader(">> Refine Information  (Values Based on Full Batches needed for Refine)");
$conversion_info->addRow("#080816");
$conversion_info->addCol("Minerals", array (
	"bold" => true,
	"colspan" => 8,
	"align" => "center"
));
$conversion_info->addRow("#080822");
$conversion_info->addCol("Mineral", array (
	"bold" => true,
	"colspan" => 2
));
$conversion_info->addCol("Quantity", array (
	"bold" => true
));
$conversion_info->addCol("Refined m3", array (
	"bold" => true
));
$conversion_info->addCol("Mineral", array (
	"bold" => true,
	"colspan" => 2
));
$conversion_info->addCol("Quantity", array (
	"bold" => true
));
$conversion_info->addCol("Refined m3", array (
	"bold" => true
));

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Tritanium.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Tritanium", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Tritanium,0));
$conversion_info->addCol(number_format($Tritanium * 0.01,2) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Pyerite.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Pyerite", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Pyerite,0));
$conversion_info->addCol(number_format($Pyerite * 0.01,2) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Mexallon.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Mexallon", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Mexallon,0));
$conversion_info->addCol(number_format($Mexallon * 0.01,2) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Isogen.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Isogen", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Isogen,0));
$conversion_info->addCol(number_format($Isogen * 0.01,2) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Nocxium.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Nocxium", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Nocxium,0));
$conversion_info->addCol(number_format($Nocxium * 0.01,2) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Zydrine.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Zydrine", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Zydrine,0));
$conversion_info->addCol(number_format($Zydrine * 0.01,2) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Megacyte.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Megacyte", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Megacyte,0));
$conversion_info->addCol(number_format($Megacyte * 0.01,2) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Morphite.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Morphite", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Morphite,0));
$conversion_info->addCol(number_format($Morphite * 0.01,2) . " m3");

$conversion_info->addRow("#080816");
$conversion_info->addCol("Ice Products", array (
	"bold" => true,
	"colspan" => 8,
	"align" => "center"
));
$conversion_info->addRow("#080822");
$conversion_info->addCol("Ice Product", array (
	"bold" => true,
	"colspan" => 2
));
$conversion_info->addCol("Quantity", array (
	"bold" => true
));
$conversion_info->addCol("Refined m3", array (
	"bold" => true
));
$conversion_info->addCol("Ice Product", array (
	"bold" => true,
	"colspan" => 2
));
$conversion_info->addCol("Quantity", array (
	"bold" => true
));
$conversion_info->addCol("Refined m3", array (
	"bold" => true
));

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Helium.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Helium Isotopes", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($HeliumIsotopes,0));
$conversion_info->addCol(number_format($HeliumIsotopes * 0.15,0) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Hydrogen.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Hydrogen Isotopes", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($HydrogenIsotopes,0));
$conversion_info->addCol(number_format($HydrogenIsotopes * 0.15,0) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Nitrogen.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Nitrogen Isotopes", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($NitrogenIsotopes,0));
$conversion_info->addCol(number_format($NitrogenIsotopes * 0.15,0) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Oxygen.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Oxygen Isotopes", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($OxygenIsotopes,0));
$conversion_info->addCol(number_format($OxygenIsotopes * 0.15,0) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/HeavyWater.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Heavy Water", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($HeavyWater,0));
$conversion_info->addCol(number_format($HeavyWater * 0.4,0) . " m3");
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/LiquidOzone.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Liquid Ozone", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($LiquidOzone,0));
$conversion_info->addCol(number_format($LiquidOzone * 0.4,0) . " m3");

$conversion_info->addRow();
$conversion_info->addCol("<img width=\"32\" height=\"32\" src=\"./images/mats/Strontium.png\">", array (
				"width" => "64"
			));
$conversion_info->addCol("Strontium Clathrates", array (
				"bold" => true
			));
$conversion_info->addCol(number_format($Strontium,0));
$conversion_info->addCol(number_format($Strontium * 3,0) . " m3", array (
				"colspan" => 5
				));

$conversion_info->addRow("#080822");
$conversion_info->addCol("Minerals Total m3:", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 7
			));
$Minm3total = (($Tritanium + $Pyerite + $Mexallon + $Isogen + $Nocxium + $Zydrine + $Megacyte + $Morphite) * 0.01);
$conversion_info->addCol(number_format($Minm3total,2) . " m3", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 1
			));
$conversion_info->addRow("#080822");
$conversion_info->addCol("Ice Product Total m3:", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 7
			));
$Icem3total = ((($HeliumIsotopes + $HydrogenIsotopes + $NitrogenIsotopes + $OxygenIsotopes) * 0.15) + (($HeavyWater + $LiquidOzone) * 0.4) + ($Strontium * 3));
$conversion_info->addCol(number_format($Icem3total,2) . " m3", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 1
			));

$conversion_info->addRow("#080822");
$conversion_info->addCol("Refined Total m3:", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 7
			));
$conversion_info->addCol(number_format(($Icem3total + $Minm3total),2) . " m3", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 1
			));

/*			
$conversion_info->addRow("#080822");
$conversion_info->addCol("Haulable m3 Savings:", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 7
			));
$conversion_info->addCol(number_format(($total_ore_m3 - $m3Remain) - ($Icem3total + $Minm3total),2) . " m3", array (
				"bold" => true,
				"align" => "right",
				"colspan" => 1
			));			
*/

?>
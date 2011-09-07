<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeOreWorth.php,v 1.19 2008/01/02 20:01:32 mining Exp $
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
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();

    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }

    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

function getMarketPrice($id, $type, $criteria) {
	
	$regionlimit = getConfig("useRegion");
	$xmlUrl = "http://api.eve-central.com/api/marketstat?typeid=" . $id . "&regionlimit=" . $regionlimit; 
	// XML feed file/URL
	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr);
	$arrXml = objectsIntoArray($xmlObj);

	return $arrXml["marketstat"]["type"][$type][$criteria];	
}

function getPriceCache($currentOre) {
	global $DB;
	
	$sql = "SELECT * FROM itemList WHERE itemName = '" . $currentOre . "' LIMIT 1";
	$priceCacheDB = $DB->query($sql);
	$priceCacheRow = $priceCacheDB->fetchRow();
	
	return $priceCacheRow["value"];
}

function makeOreWorth() {
	// Get the globals.
	global $TIMEMARK;
	global $ORENAMES;
	global $DBORE;
	global $DB;
	global $OTYPENAME;
	global $PRICECRITERIA;

	// Where do I get Ore Values?
	
	$Market = getConfig("useMarket");
	
	IF($Market == 1) {
		// Update prices from Eve-Central and store.
		
		$CURRENTTIME = date(U) - (getConfig("timeOffset") * 60 * 60);
		$itemListDB = $DB->query("SELECT * FROM `itemList` ORDER BY `itemName` DESC");
		$orderType = $OTYPENAME[getConfig("orderType")];
		$priceCrit = $PRICECRITERIA[getConfig("priceCriteria")];
				
		for($i = 0; $i <= $itemListDB->numRows(); $i++) {
			$itemInfo = $itemListDB->fetchRow();
			$quoteAge = $CURRENTTIME - $itemInfo['updateTime'];
			if($quoteAge >= 3600) {
				$currentPrice = getMarketPrice($itemInfo['itemID'], $orderType, $priceCrit);
				$DB->query("UPDATE itemList SET `updateTime` = $CURRENTTIME, `value` = $currentPrice WHERE `itemID` = " . $itemInfo['itemID']);
			}
		}
	} else {
		// load the values.
		$orevaluesDS = $DB->query("select * from orevalues order by id DESC limit 1");
		$orevalues = $orevaluesDS->fetchRow();
	};
	
	// Create the table.
	$table = new table(8, true);
	$table->addHeader(">> Manage ore values", array (
		"bold" => true,
		"colspan" => 8
	));

	$table->addRow();
	$table->addCol("Ore Name", array (
		"colspan" => 2,
		"bold" => true
	));
	$table->addCol("Enabled", array (
		"bold" => true
	));
	$table->addCol("Value", array (
		"bold" => true
	));
	$table->addCol("Ore Name", array (
		"colspan" => 2,
		"bold" => true
	));
	$table->addCol("Enabled", array (
		"bold" => true
	));
	$table->addCol("Value", array (
		"bold" => true
	));

	// How many ores are there in total? Ie, how long has the table to be?
	$tableLength = ceil(count($ORENAMES) / 2)-1;

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
		if (getOreSettings($DBORE[$ORE])) {
			$table->addCol("<input name=\"" . $DBORE[$ORE] . "Enabled\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
		} else {
			$table->addCol("<input name=\"" . $DBORE[$ORE] . "Enabled\" value=\"true\" type=\"checkbox\">");
		}
		IF($Market == 1) {
			$thisPrice = getPriceCache($ORE);
			$table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$DBORE[$ORE]\"" . "size=\"10\" value=\"" . $thisPrice . "\">");
		} else {
			$table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$DBORE[$ORE]\"" . "size=\"10\" value=\"" . $orevalues[$DBORE[$ORE] . Worth] . "\">");
		}
		// Ore columns for RIGHT side.
		$ORE = $ORENAMES[$i + $tableLength +1];
		
		// Fetch the right image for the ore.
		$ri_words = str_word_count($ORE, 1);
		$ri_max = count($ri_words);
		$ri = strtolower($ri_words[$ri_max -1]);
		
		if ($ORE != "") {
			$table->addCol("<img width=\"32\" height=\"32\" src=\"./images/ores/" . $ORE . ".png\">");
			$table->addCol($ORE);
			if (getOreSettings($DBORE[$ORE])) {
				$table->addCol("<input name=\"" . $DBORE[$ORE] . "Enabled\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
			} else {
				$table->addCol("<input name=\"" . $DBORE[$ORE] . "Enabled\" value=\"true\" type=\"checkbox\">");
			}
		IF($Market == 1) {
			$thisPrice = getPriceCache($ORE);
			$table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$DBORE[$ORE]\"" . "size=\"10\" value=\"" . $thisPrice . "\">");
		} else {
			$table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$DBORE[$ORE]\"" . "size=\"10\" value=\"" . $orevalues[$DBORE[$ORE] . Worth] . "\">");
		}
		} else {
			$table->addCol("");
			$table->addCol("");
			$table->addCol("");
			$table->addCol("");
		}

	}

	$form .= "<input type=\"hidden\" name=\"action\" value=\"changeore\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$form .= "<input type=\"submit\" name=\"change\" value=\"Modify ore settings\">";
	$table->addHeaderCentered($form, array (
		"colspan" => 8,
		"align" => "center"
	));

	// return the page
	return ("<h2>Modify ore settings</h2><form action=\"index.php\"method=\"post\">" . $table->flush());
}
?>
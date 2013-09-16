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

function getMarketPrice($id, $type = "buy", $criteria = "max") {
	global $TIMEMARK;
	global $DB;
	$cacheResult = $DB->query("select value from itemList where itemID = '$id' and updateTime > '".($TIMEMARK - (60 * 60 * 24 * 7))."';");
	
	if($cacheResult->numRows() == 0){
		$regionlimit = getConfig("useRegion");
		$xmlUrl = "http://api.eve-central.com/api/marketstat?typeid=" . $id . "&regionlimit=" . $regionlimit; 
		// XML feed file/URL
		$xmlStr = file_get_contents($xmlUrl);
		$xmlObj = simplexml_load_string($xmlStr);
		$arrXml = objectsIntoArray($xmlObj);
		$value = $arrXml["marketstat"]["type"][$type][$criteria];
		
		$DB->query("insert into itemList (updateTime, itemID, value) 
				select '$TIMEMARK','$id','$value' from dual 
				where (select count(*) from itemList where itemID = '$id') = 0");
		$DB->query("update itemList set value = '$value', updateTime = '$TIMEMARK' where itemID = '$id'");
		//var_dump($value);
		return $value;
	}else{
		while($r2 = $cacheResult->fetchRow()){
			return $r2['value'];
		}
	}
}

function getXMLobj($xmlUrl){
	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr);
	return objectsIntoArray($xmlObj);
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
	
	if($Market) {
		// Update prices from Eve-Central and store.
		if($Market == "eve-central"){
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
		}else if($Market == "eve-marketdata"){
		
		}else if($Market == "eve-marketeer"){
			
			$regionID = getConfig("useRegion");
			$url = "http://www.evemarketeer.com/api/info/$itemID/xml/$regionID/buy_highest5";
			$xml = getXMLobj($url);
			echo $xml['row']['buy_highest5'];
		}
	} else {
		// load the values.
		$orevaluesDS = $DB->query("select a.item, a.Worth, a.time, a.modifier from orevalues a where time = (select max(time) from orevalues b where a.item = b.item) group by item ORDER BY time DESC");
		while($row = $orevaluesDS->fetchRow())
			$orevalues[$row['item']] = $row;
	};
	
	if($Market) {
		$headerText = ">> Manage ore values<br><font color=\"#ff0000\"><b>Ore values are current market values.</b></font>";
	} else {
		$headerText = ">> Manage ore values";
	}
	
	// Create the table.
	$table = new table(8, true);
	$table->addHeader($headerText, array (
		"bold" => true,
		"colspan" => 8
	));

	$OPTYPE = (isset($_REQUEST['optype']) && $_REQUEST['optype'] != "")?$_REQUEST['optype']:"";
	
	$table->addRow();
	$table->addCol("Op Type:");
	$ops = $DB->getAll("select opName from opTypes;");
	if($DB->isError($ops)){
		die($ops->getMessage());
	}
	$opSelect = "<select name='optype' onChange='window.location = \"?action=changeow&optype=\"+this.value'>\n";
	$opSelect .= "<option value=''>Standard</option>\n";
	foreach($ops as $op){
		$default = $op['opName'] == $OPTYPE?"selected":"";
		$opSelect .= "<option $default value='".$op['opName']."'>".$op['opName']."</option>\n";
	}
	$opSelect .= "</select>";
	
	$table->addCol($opSelect, array("colspan"=>7));
	
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

	$orevaluesDS = $DB->query("select REPLACE(REPLACE(itemName,' ',''),'-','') as item, (select Worth from orevalues a where REPLACE(REPLACE(itemName,' ',''),'-','') = a.item order by time desc limit 1) as Worth, itemID as typeID, itemName as typeName from itemList t order by typeID");

    $flip = false;
    while($row = $orevaluesDS->fetchRow()){
        if(!$flip){
		    $table->addRow();
            $flip = true;
        }else{
            $flip = false;
        }

        $table->addCol("<img width=\"32\" height=\"32\" src=\"http://image.eveonline.com/Type/$row[typeID]_32.png\">");
        $table->addCol($row['typeName']);
        if (getOreSettings($row['item'],$OPTYPE)) {
            $table->addCol("<input name=\"" . $row['item'] . "Enabled\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
        } else {
            $table->addCol("<input name=\"" . $row['item'] . "Enabled\" value=\"true\" type=\"checkbox\">");
        }
        IF($Market == 1) {
            $thisPrice = getPriceCache($row['typeName']);
            $table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$row[item]\"" . "size=\"10\" value=\"" . $thisPrice . "\">");
        } else {
            $table->addCol("<input type=\"text\" style=\"text-align: right\" name=\"$row[item]\"" . "size=\"10\" value=\"" . $row['Worth'] . "\">");
        }
	}
	
	
	$form = "<input type=\"hidden\" name=\"action\" value=\"changeore\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$form .= "<input type=\"hidden\" name=\"optype\" value=\"$OPTYPE\">";
	$form .= "<input type=\"submit\" name=\"change\" value=\"Modify ore settings\">";
	$table->addHeaderCentered($form, array (
		"colspan" => 8,
		"align" => "center"
	));

	// return the page
	return ("<h2>Modify ore settings</h2><form action=\"index.php\"method=\"post\">" . $table->flush());
}
?>

<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/changeOreValue.php,v 1.13 2008/01/02 20:01:32 mining Exp $
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

function changeOreValue() {

	// Import global Variables and the Database.
	global $DB;
	global $ORENAMES;
	global $DBORE;
	global $TIMEMARK;
	global $MySelf;

	// Are we allowed to change this?
	if (!$MySelf->canChangeOre()) {
		makeNotice("You are not allowed to fiddle around in there!", "error", "forbidden");
	}

	// Lets set the userID(!)
	$userID = $MySelf->getID();

	$OPTYPE = isset($_POST['optype'])?$_POST['optype']:"";
	$opdirect = isset($_POST['optype'])?"&optype=$OPTYPE":"";

	foreach($_POST["newItem"] as $item){
		$DB->query("insert into itemList (itemName) values ('$item')");
	}

	$DB->query("update `itemList` set `friendlyName` = replace(replace(replace(`itemName`,' ',''),'-',''),'.','')");

	$oreTypes = $DB->query("select friendlyName as item, (select Worth from orevalues a where friendlyName = a.item order by time desc limit 1) as Worth, itemID as typeID, itemName as typeName from itemList t order by typeID");
	// Now loop through all possible oretypes.
	while($row = $oreTypes->fetchRow()){
		$ORE = $row['item'];
		// But check that the submited information is kosher.
		if ((isset ($_POST[$row['item']])) && (is_numeric($_POST[$row['item']]))) {
			// Insert the new ore values into the database.
			$DB->query("insert into orevalues (modifier, time, Item, Worth) 
				select $userID,$TIMEMARK,'$ORE','$_POST[$ORE]' from dual 
				where (
					select worth from orevalues where item = '$ORE' and time = (
						select max(time) from orevalues where item = '$ORE')
					) != '$_POST[$ORE]' or (select count(*) from orevalues where item = '$ORE') = 0"
				);
			
			/*
			// Write the new, updated values.
			$DB->query("UPDATE orevalues SET " . $ORE . "Worth= '$_POST[$ORE]' WHERE time = '$TIMEMARK'");
			*/
			
			// Enable or disable the oretype.
			if (isset($_POST[$ORE . 'Enabled']) && $_POST[$ORE . 'Enabled']) {
				$DB->query("insert into `config` (value,name) values ('1','" . $ORE . $OPTYPE . "Enabled')");
				$DB->query("UPDATE config SET value = '1' where name='" . $ORE . $OPTYPE . "Enabled'");
			} else {
				$DB->query("UPDATE config SET value = '0' where name='" . $ORE . $OPTYPE . "Enabled'");
			}
		}

		if($row['typeID'] == 0){
			$itemID = getItemIDFromName($row["typeName"]);
			$DB->query("UPDATE itemList set itemID = $itemID where itemName = '$row[typeName]'");
		}
	}

	// Let the user know.         
	makeNotice("The payout values for ore have been changed.", "notice", "New data accepted.", "index.php?action=changeow$opdirect", "[OK]");
}

function getItemIDFromName($itemName){
        $url = "https://www.fuzzwork.co.uk/api/typeid.php?typename=";
        $json = file_get_contents($url . urlencode($itemName));
        $object = json_decode($json,true);
        return $object["typeID"];
}

?>

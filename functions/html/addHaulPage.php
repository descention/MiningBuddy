<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/addHaulPage.php,v 1.28 2008/01/05 14:47:38 mining Exp $
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
* addhaulpage()
* Prints the form to add a haul-
*/
function addhaulpage() {

	// Needed globals
	global $DB;
	global $ORENAMES;
	global $DBORE;
	global $MySelf;

	// Some needed variables
	$USER = $MySelf->getID();
	$ORESETTINGS = getOreSettings();

	// Get the run the user is on.
	if (!empty ($_GET[id])) {
		// We supplied our own ID.
		$ID = sanitize((int) $_GET[id]);
		numericCheck($_GET[id], 0);
	} else {
		// No idd supplied, get our own :P
		$ID = userInRun($MySelf->getID());
	}

	//   No ID found.
	if (!$ID) {
		makeNotice("Either you have selected an invalid run, you have not joined that run or it is no longer open.", "warning", "Unable to register your haul");
	}
	
	$OPTYPE = $DB->getCol("select optype from runs where id = $ID");
	$OPTYPE = $OPTYPE[0];
	
	// Create the table!
	$haulpage = new table(2, true);
	$mode = array (
		"bold" => true,
		"align" => "right"
	);
	$haulpage->addHeader(">> Register new Hauling");
	$haulpage->addRow();
	if($OPTYPE=="Shopping"){
		$haulpage->addCol("Shopping for Op: #<a href=\"index.php?action=show&id=$ID\">" . str_pad($ID, 5, "0", STR_PAD_LEFT) . "</a> Add *positive* values for purchases", array (
			"align" => "left"
		));	
	} else {
		$haulpage->addCol("Hauling for Op: #<a href=\"index.php?action=show&id=$ID\">" . str_pad($ID, 5, "0", STR_PAD_LEFT) . "</a>", array (
			"align" => "left"
		));
	}
	/*
	// fetch the system the haul is taking place in..
//	$location = $DB->getCol("select location from runs where endtime is NULL and id='$ID' order by id desc limit 1");
//	$runLocation = $location[0];
	$runLocation = getLocationOfRun($ID);
	
	// make the targeted system click-able.
	$sytem = new solarSystem($runLocation);
	
	// Assemble a PDM with all the destinations for the current run.
	$locations = $DB->query("SELECT location FROM hauled WHERE miningrun='$ID' ORDER BY location ASC");
	if ($locations->numRows()) {
		while ($loc = $locations->fetchRow()) {
			if ($loc[location] != "") {
				$pdmSystems[] = $loc[location];
			}
		}
	}
	
	// Get the location the last haul was brought to at.
	$lastHaulLocation = $DB->getCol("SELECT location FROM hauled WHERE miningrun='$ID' AND hauler='".$MySelf->getID()."' ORDER BY time DESC LIMIT 1");
	$lastHaulLocation = $lastHaulLocation[0];
	
	// Get a list of neighbouring systems.
	$neighbouringSystems = $sytem->getNeighbouringSystems();
	
	// Lets pick the right system.
	if($lastHaulLocation) {
		// Use the last system stuff was hauled to.
		$location = $lastHaulLocation;
	} else {
		// Or, if thats empty, the system the op is in.
		$location = $runLocation;
	}

	if (is_array($pdmSystems)) {
		$Systems = array_merge($neighbouringSystems,$pdmSystems);
	} else {
		$Systems = $neighbouringSystems;
	}
	sort($Systems);
	
//	unset($pdmSystems);
//	unset($neighbouringSystems);
//	unset($loc);
//	unset($locations);
	
	foreach($Systems as $s) {
		if ($s == $location) {
			$pdm .= "<option value=\"".strtolower($s)."\" SELECTED>".ucfirst($s)."</option>";
		} else {
			$pdm .= "<option value=\"".strtolower($s)."\">".ucfirst($s)."</option>";
		}
	}
	$pdm = "<select name=\"location\">" . $pdm . "</select>";
	*/
	$haulpage->addCol("System hauling to: ".$pdm." -or- <input type=\"text\" name=\"location2\" value=\"\">", array (
		"align" => "right"
	));
	$haulpage->addRow();
	$haulpage->addCol("<hr>", array (
		"colspan" => "2"
	));

	$haulpage->addRow();
	$haulDumpScript = "<script>
function parseDump(sender){
  var items = sender.value.split(\"\\n\");
  for(var x =0;x< items.length;x++){
    var item = items[x].split(\"\\t\");
    document.getElementsByName(item[0].replace(' ',''))[0].value = item[1].replace(',','');
  }
}
</script>";
	$haulpage->addCol("$haulDumpScript<textarea cols='50' rows='4' name='dumpHaul' onblur='parseDump(this)'></textarea>", array("colspan"=>"2"));

	// Now we need the sum of all ores. 
	$totalOres = count($ORENAMES);

	/*
	// And the sum of all ENABLED ores.
	$totalEnabledOres = $DB->getCol("select count(name) as active from config where name LIKE '%Enabled' AND value='1'");
	$totalEnabledOres = $totalEnabledOres[0];
	*/

	/*
	 * This is evil. We have to create an array that we fill up sorted.
	 * It aint cheap. First, we loop through all the ore values.
	 */
	for ($p = 0; $p < $totalOres; $p++) {
		// Then we check each ore if it is enabled.
		$ORE = $DBORE[$ORENAMES[$p]];
		if (getOreSettings($ORE,$OPTYPE)) {
			// If the ore is enabled, add it to the array.
			$left[] = $ORE;
		} else {
			// add to disabled-array.
			$disabledOres[] = $ORE;
		}
	}
	$totalEnabledOres = count($left);
	
	// No ores enabled?
	if ($totalEnabledOres == 0 && $OPTYPE != "Shopping") {
		makeNotice("Your CEO has disabled *all* the Oretypes. Please ask your CEO to reactivate at leat one Oretype.", "error", "No valid Oretypes!");
	}
	$ajaxHaul = isset($_GET[ajaxHaul]);
	if($ajaxHaul || $OPTYPE == "Shopping"){
		$haulpage->addRow();
		$script = "<script>
var selectedItems = \"\";
var currentQuery;
var int;
function lookForItem(txt){
	currentQuery = txt;
	clearInterval(int);
	if(txt.value.length>2){
		var int=self.setInterval('execQuery()',2000);
	}
}";
$script .= "
function execQuery(){
	clearInterval(int);
	var txt = currentQuery;
	\$.ajax({
		url: 'index.php?action=getItemList&ajax&q=' + txt.value,
		success: function(data){\$('#ajaxItemList').html(data);}
	});
	
}";
$script .= "
function addItem(selection){
	//$(selection).animate({background-color:yellow;});
	var item = selection.innerHTML;
	var dbore = selection.name;
	//$(selection).animate({background-color:none;});
	if(selectedItems.split(',').indexOf(item) == -1 ){
		var print = \$('#selectedItemList').html() + '<div>Add <input type=\"text\" size=\"5\" name=\"' + dbore + '\" value=\"0\">' + item + '</div>';
		\$('#selectedItemList').html(print);
		if(selectedItems.length == 0){
			selectedItems = item;
		} else {
			selectedItems += ',' + item;
		}
	}
}
</script> ";

		$haulpage->addCol("Search for an item:<input name='itemSearch' onkeyup='lookForItem(this)' />, then click the item name below.",array("colspan"=>2));
		$haulpage->addRow();
		$haulpage->addCol("<div id='selectedItemList'></div>",array("colspan"=>2));
		$haulpage->addRow();
		$haulpage->addCol("<div id='ajaxItemList'></div>",array("colspan"=>2));
		
	} else {
		// The table is, rounded up, exactly half the size of all enabled ores.
		$tableLength = ceil($totalEnabledOres / 2);
		
		// Now, copy the lower second half into a new array.
		$right = array_slice($left, $tableLength);

		/*
		 * So now we have an array of all the enabled ores. All we
		 * need to do now, is create a nice, handsome table of it.
		 * Loop through this array.
		 */
		for ($i = 0; $i < $tableLength; $i++) {

			// Fetch the right image for the ore.
			$ri_words = str_word_count(array_search($left[$i], $DBORE), 1);
			$ri_max = count($ri_words);
			$ri = strtolower($ri_words[$ri_max -1]);

			// Add a row.
			$haulpage->addRow();

			// left side.
			$haulpage->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . array_search($left[$i], $DBORE) . ".png\">" .
			"Add <input type=\"text\" size=\"5\" name=\"$left[$i]\" value=\"0\"> " . array_search($left[$i], $DBORE));

			// We need an ore type (just in case of odd ore numbers)
			if ($right[$i] != "") {
				// right side.

				// Fetch the right image for the ore.
				$ri_words = str_word_count(array_search($right[$i], $DBORE), 1);
				$ri_max = count($ri_words);
				$ri = strtolower($ri_words[$ri_max -1]);

				// Add the column.
				$haulpage->addCol("<img width=\"20\" height=\"20\" src=\"./images/ores/" . array_search($right[$i], $DBORE) . ".png\">" .
				"Add <input type=\"text\" size=\"5\" name=\"" . $right[$i] . "\" value=\"0\"> " . array_search($right[$i], $DBORE));
			} else {
				// We have an odd number of ores: add empty cell.
				$haulpage->addCol("");
			}

		}
	}
	/*
	// Print out all disabled ore types:
	$disabledOreCount = count($disabledOres);

	// add the "," between words, but not before the first one, and an "and" between the last one.
	for ($i = 0; $i < $disabledOreCount; $i++) {
		if ($disabledOreCount == $i +1) {
			$disabledOresText .= " and " . array_search($disabledOres[$i], $DBORE);
		} else
			if (empty ($disabledOresText)) {
				$disabledOresText = array_search($disabledOres[$i], $DBORE);
			} else {
				$disabledOresText .= ", " . array_search($disabledOres[$i], $DBORE);
			}
	}

	// Display the ore-disables-disclaimer. (Only if there are disabled oretypes.)
	if (!empty ($disabledOresText)) {
		$disabledOresText = "The following Oretypes has been disabled by the CEO: $disabledOresText.";
	}
	*/
	
	
	
	$haulpage->addRow();
	$haulpage->addCol("<hr>", array (
		"colspan" => "2"
	));

	$haulpage->addHeaderCentered("<input type=\"submit\" name=\"haul\" value=\"Commit haul to database\">");

	// Render the page...
	$form_stuff .= "<input type=\"hidden\" value=\"check\" name=\"check\">";
	$form_stuff .= "<input type=\"hidden\" value=\"addhaul\" name=\"action\">";
	$form_stuff .= "<input type=\"hidden\" value=\"" . $ID . "\" name=\"id\">";
	$form_stuff .= "</form>";
	$html = "<h2>Submit new transport manifest (<a href='?".$_SERVER['QUERY_STRING']."&ajaxHaul'>ajax</a>)</h2><form action=\"index.php\" method=\"post\">" . $haulpage->flush() . $form_stuff;

	/*
	// print out all the disabled oretypes.
	if (!empty ($disabledOresText)) {
		$page .= "<br><i>" . $disabledOresText . "</i>";
	}*/

	// Return the page
	return ( $script . $html . $page);

}
?>

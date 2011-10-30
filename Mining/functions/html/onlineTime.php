<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/onlineTime.php,v 1.24 2008/01/02 20:01:32 mining Exp $
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
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT Ssans THE COPYRIGHT
 *  OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 *  TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 *  OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 *  OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

function onlineTime() {

	// We need funky globals!
	global $DB;
	global $MySelf;
	global $TIMEMARK;

	/*
	 * The change form.
	 */

	$MySetting = $DB->query("SELECT * FROM onlinetime WHERE userid='" . $MySelf->getID() . "'");
	$MySetting = $MySetting->numRows();

	if ($MySetting == 0 || $_GET[edit]) {

		$setTable = new table(3, true);
		$setTable->addHeader(">> Set your online time");

		// Fetch el grande saved array. Ole!
		$myOnlineTime = $DB->getAssoc("SELECT * FROM onlinetime WHERE userid='" . $MySelf->getID() . "' LIMIT 1");
		$myOnlineTime = $myOnlineTime[$MySelf->getID()];

		// Loop through the hours.
		$j = 0;
		for ($i = 0; $i <= 23; $i++) {

			// Add a new row every 3rd time we are here, startign with first.
			if ($j == 0) {
				$setTable->addRow();
				$j = 3;
			}

			// 01-02, 02-03.. etc
			$p = str_pad($i, 2, "0", STR_PAD_LEFT) . "-" . str_pad(($i +1), 2, "0", STR_PAD_LEFT);

			// Wow this is ugly. Pre-select all values that are stored in the db.
			$ppdv0 = "<option value=\"0\">0</option>";
			$ppdv1 = "<option value=\"1\">1</option>";
			$ppdv2 = "<option value=\"2\">2</option>";
			$ppdv3 = "<option value=\"3\">3</option>";

			$column = "h" . str_pad($i, 2, "0", STR_PAD_LEFT);
			switch ($myOnlineTime[$column]) {
				case ("0") :
					$ppdv0 = "<option selected value=\"0\">0</option>";
					break;
				case ("01") :
					$ppdv1 = "<option selected value=\"1\">1</option>";
					break;
				case ("2") :
					$ppdv2 = "<option selected value=\"2\">2</option>";
					break;
				case ("3") :
					$ppdv3 = "<option selected value=\"3\">3</option>";
					break;
			}

			$ppd = $ppdv0 . $ppdv1 . $ppdv2 . $ppdv3;

			$s = "<select name=\"$i\">";
			$setTable->addCol($p . $s . $ppd . "</select>");

			// Substract one.
			$j--;
		}

		// explain:
		$setTable->addRow();
		$setTable->addCol("Code 0: You cant play at all. (sleep, work)", array (
			"colspan" => 3
		));
		$setTable->addRow();
		$setTable->addCol("Code 1: You could, but normaly wouldnt, except for extreme cases.", array (
			"colspan" => 3
		));
		$setTable->addRow();
		$setTable->addCol("Code 2: You can easily be online, but normaly are not.", array (
			"colspan" => 3
		));
		$setTable->addRow();
		$setTable->addCol("Code 3: Your preffered online time.", array (
			"colspan" => 3
		));
		$submitbutton = "<input type=\"hidden\" name=\"check\" value=\"true\">" .
		"<input type=\"hidden\" value=\"modonlinetime\" name=\"action\">" .
		"<input type=\"submit\" value=\"Update your OnlineTime\" name=\"submit\">";
		$setTable->addHeaderCentered("All times are EvE time!");
		$setTable->addHeaderCentered($submitbutton);

		$form .= "<form action=\"index.php\" method=\"POST\">";
		$form .= $setTable->flush();
		$form .= "</form>";

	} else {
		$editLink = "<br>[<a href=\"index.php?action=onlinetime&edit=true\">Edit your times</a>]";
	}

	$page = "<h2>Online Time</h2>" . $form;

	/*
	 * Okay pheew. That was the table to set your own time. Now lets create
	 * a table to show everyones online time.
	 */

	$onlineTime = new table(25, true);
	$onlineTime->addHeader(">> Online Time of your corporation");
	$onlineTime->addRow("#060622");
	$onlineTime->addCol("Member");
	$onlineTime->addCol("00");
	$onlineTime->addCol("01");
	$onlineTime->addCol("02");
	$onlineTime->addCol("03");
	$onlineTime->addCol("04");
	$onlineTime->addCol("05");
	$onlineTime->addCol("06");
	$onlineTime->addCol("07");
	$onlineTime->addCol("08");
	$onlineTime->addCol("09");
	$onlineTime->addCol("10");
	$onlineTime->addCol("11");
	$onlineTime->addCol("12");
	$onlineTime->addCol("13");
	$onlineTime->addCol("14");
	$onlineTime->addCol("15");
	$onlineTime->addCol("16");
	$onlineTime->addCol("17");
	$onlineTime->addCol("18");
	$onlineTime->addCol("19");
	$onlineTime->addCol("20");
	$onlineTime->addCol("21");
	$onlineTime->addCol("22");
	$onlineTime->addCol("23");

	// Ask the oracle. 	
	$cutOff =$TIMEMARK - 2592000; // 30 days.
	$OT = $DB->getCol("select distinct id from users where canLogin='1' and lastlogin >= '$cutOff'  AND deleted='0'");

	// Pilots names are not store in the onlinetable. So we have to translate.
	foreach ($OT as $pilotID) {
		$pilots[] = idToUsername($pilotID);
	}

	// Anyone published his online time yet?
	if (count($pilots) >= 1) {
		$haveOnlineTime = true;
	}

	// Sort the pilots by name.
	asort($pilots);

	// Create a row for each pilot.
	foreach ($pilots as $pilot) {

		// Get the pilots online times.
		$id = usernameToID($pilot);
		$ot = $DB->query("SELECT * FROM onlinetime WHERE userid='" . $id . "'");

		// break off here if the user has not publishes his online time yet.
		if ($ot->numRows() == 0) {
			continue;
		}		
		
		$ot = $ot->fetchRow();
		$onlineTime->addRow();

		// Pilot name
		$onlineTime->addCol(ucfirst($pilot));
		

		// And go through each hour, creating a nice coloured box.
		for ($i = 0; $i <= 23; $i++) {
			$column = "h" . str_pad($i, 2, "0", STR_PAD_LEFT);

			// #222733  |  #4f646e  |  #c2c957  |  #e6f137

			switch ($ot[$column]) {
				case ("0") :
					$onlineTime->addCol(" ", array (
						"bgcolor" => "#222733"
					));
					break;
				case ("01") :
					$onlineTime->addCol(" ", array (
						"bgcolor" => "#4f646e"
					));
					break;
				case ("2") :
					$onlineTime->addCol(" ", array (
						"bgcolor" => "#c2c957"
					));
					break;
				case ("3") :
					$onlineTime->addCol(" ", array (
						"bgcolor" => "#e6f137"
					));
					break;
			}

		}
	}

	// Return the hard labor.

	/* Return the Online Table, or, If no one published his online time yet,
	 * print a message saying just that. */

	if ($haveOnlineTime) {
		// We have at least one person who sent in his times.
		return ($page . $onlineTime->flush() . $editLink);
	} else {
		// No one ever sent in his times. 
		return ($page . "<b>No one sent in his/her onlinetimes yet. But you can be the first! :)</b><br>" . $editLink);
	}
}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun.php,v 1.52 2008/01/12 15:53:12 mining Exp $
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
* listRun()
* will return the run id requested in the GET array and
* print a nice, friendly html page.
*/

function listRun() {

	/*
	 * STEP ZERO:
	 * Import variables, and define needed things.
	 */
	global $DB; // Database connection
	global $STATIC_DB; // static db name
	global $ORENAMES; // A list of all the orenames
	global $DBORE; // An array of db friendly orenames
	global $TIMEMARK; // The "current" timestamp
	global $MySelf; // Ourself, and along with that, the permissions.
	global $SHIPTYPES;	// We dont want numbers to memorize.
	global $DBSHIP;	// An array of db friendly shipnames
	global $MATERIALS;
	global $DBMAT;
	$userID = $MySelf->getID(); // Shortcut: Assign the UserID to userID.
	$common_mode = array (
			//		"bold" => true,
	"width" => "200"
	); // Default column mode here.

	/*
	 * STEP ONE:
	 * Load the database row into $row. This requires us to look up the run ID
	 * first.
	 */

    /*
     * Inside:
     * Database business ONLY
     */

// We have to SELECT the most fitting ID. This can be done in three ways.
    if (($_GET['id'] >= 0) && (is_numeric($_GET['id']))) {
        // Way Nr. 1: The user specified an ID.
        $ID = sanitize($_GET['id']);
    } else {
        // Way Nr. 2: The user is in a Mining run, but has not given us an ID. Use the joined MiningOP ID.
        $ID = userInRun($userID, "check");
        if (!$ID) {
            // Way Nr. 2: The user is not in a run and has not given us an ID. Select the most up to date, not-yet-closed OP.
            $results = $DB->query("SELECT * FROM runs WHERE endtime is NULL order by id desc limit 1");
            if (($results->numRows()) == "0") {
                // Total failure: No operations in Database!
                MakeNotice("There are no mining operations in the database! You have to create an operation prior to join.", "warning", "Not joined");
            }
            $getid = $results->fetchRow();
            $ID = $getid['run'];
        }
    }

// Now lets fetch the Dataset.
    $select = "";
    $r = $DB->query("select item, sum(Quantity) as total from hauled where miningrun = '$ID' group by item having sum(Quantity) <> 0");
    while($r2 = $r->fetchRow()){
        if($r2['total'] != 0){
            $select .= ", '".$r2['total']."' as ".$r2['item'];
        }
    }

    $results = $DB->query("SELECT id,location,starttime,endtime,supervisor,corpkeeps,isOfficial,isLocked,oreGlue,shipGlue,tmec, optype $select FROM runs WHERE id = '$ID' limit 1");

// And check that we actually suceeded.
    if ($results->numRows() != 1) {
        makeNotice("Internal error: Could not load dataset FROM Database.", "error", "Internal Error!");
    } else {
        $row = $results->fetchRow();
    }

    // Now that we have the run loaded in RAM, we can load several other things.
    $joinlog = $DB->query("SELECT * FROM joinups WHERE run = '$ID' order by ID DESC");
    $activelog = $DB->query("SELECT * FROM joinups WHERE run = '$ID' and parted is NULL");
    if (!isset($row['shipGlue']) || $row['shipGlue'] <= 0) {
        $values = $DB->query("SELECT * FROM shipvalues order by id desc limit 1");
    } else {
        $values = $DB->query("SELECT * FROM shipvalues WHERE id='" . $row['shipGlue'] . "' limit 1");
    }
    if ($row['oreGlue'] <= 0) {
        $ovaluesR = $DB->query("select item, Worth, time, modifier from orevalues a where time = (select max(time) from orevalues b where a.item = b.item) group by item ORDER BY time DESC");
        while($oRow = $ovaluesR->fetchRow())
            $ovalues[$oRow['item']] = $oRow;
    } else {
        $ovaluesR = $DB->query("select item, Worth, time, modifier from orevalues a where time = (select max(time) from orevalues b where a.item = b.item and time <= '".$row['oreGlue']."') group by item ORDER BY time DESC");
        while($oRow = $ovaluesR->fetchRow())
            $ovalues[$oRow['item']] = $oRow;
    }




// Load cargo container database.
    if (getConfig("cargocontainer")) {
        $CansDS = $DB->query("SELECT id, location, droptime, name, pilot, isFull, miningrun FROM cans WHERE miningrun='$ID' ORDER BY droptime ASC");
    }

// note: hauling DB queries have been move into the according step-file

	/*
	 * STEP TWO
	 * Gather some vital information.
	 */

// Create a new table for the general info.
    $general_info = new table(2, true);

// Header
    $general_info->addHeader(">> General Information");

// Row: Mining Run ID
    $general_info->addRow();
    $general_info->addCol("Mining ID:", $common_mode);
    $general_info->addCol(str_pad($row['id'], 5, "0", STR_PAD_LEFT));

// Row: Is official run?
    $general_info->addRow();
    $general_info->addCol("This run is official:", $common_mode);
    $general_info->addCol(yesno($row['isOfficial'], true));

// Row: Op Type
    $general_info->addRow();
    $general_info->addCol("Op Type:", $common_mode);
    $general_info->addCol($row['optype']==""?"Standard":$row['optype']);

// Row: Supervisor Name
    $general_info->addRow();
    $general_info->addCol("Supervisor:", $common_mode);
    $general_info->addCol(makeProfileLink($row['supervisor']));

// Row: Taxes
    $general_info->addRow();
    $general_info->addCol("Corp Taxes:", $common_mode);
    $general_info->addCol($row['corpkeeps'] . ".0%");

// Row: Starttime
    $general_info->addRow();
    $general_info->addCol("Starttime:", $common_mode);
    $general_info->addCol(date("d.m.y H:i", $row['starttime']));

// Row: Endtime

    if ($row['endtime'] == "") {

        // Run is still open.
        $endtime = "ACTIVE";
        $general_info->addRow();
        $general_info->addCol("Endtime:", $common_mode);

        // Row: Endtime
        $time = numberToString($TIMEMARK - $row['starttime']);
        $secRunTime= $TIMEMARK - $row['starttime'];
        if ($time) {
            $general_info->addCol("<font color=\"#00ff00\">ACTIVE for " . numberToString($secRunTime) . "</font>");
        } else {
            $general_info->addCol("Event has not started yet.");
        }

        // Row: Corporation keeps %
        $general_info->addRow();
        $general_info->addCol("Corporation keeps:", $common_mode);
        $general_info->addCol($row['corpkeeps']."% of gross value.");

        // Current TMEC
        $general_info->addRow();
        $general_info->addCol("Current TMEC:");
        $general_info->addCol(calcTMEC($row['id'], true));

        // Statistical breakdown
        $totalISK = getTotalWorth($ID);
        if ($totalISK > 0) {
            $closed = $DB->getCol("SELECT endtime FROM runs WHERE id='" . $ID . "' LIMIT 1");
            if ($closed[0] < 1) {
                $general_info->addRow();
                $general_info->addCol("Total ISK so far:");
                $general_info->addCol(number_format($totalISK, 2) . " ISK");


                $general_info->addRow();
                $general_info->addCol("ISK per hour:");
                $general_info->addCol(number_format(($totalISK / ($secRunTime/60)) * 60) . " ISK");

            }

        }

        // Row: Actions
        $general_info->addRow();
        $general_info->addCol("Actions:", $common_mode);

        // Lets switch wether the user is currently in this run or not.
        $jointime = userInRun($MySelf->getUsername(), $ID);
        if ($jointime == "none") {
            // Is NOT in this run, give option to join.
            if (!runIsLocked($ID)) {
                if ($MySelf->canJoinRun()) {
                    $join = "[<a href=\"index.php?action=joinrun&id=$ID\">Join this OP</a>]";
                } else {
                    $join = "You are not allowed to join operations.";
                }
            } else {
                $join = (ucfirst(runSupervisor($ID)) . " has locked this run.");
            }
        } else {
            // User IS in this run.

            // Are we allowed to haul?
            if (($row['endtime'] == "") && ($MySelf->canAddHaul())) {
                $addHaul = " [<a href=\"index.php?action=addhaul&id=$ID\">Haul</a>] ";
            } else {
                $addHaul = false;
            }

            // Run-Owner: Lock/Unlock run (to disallow people joining)
            if (runSupervisor($row['id']) == $MySelf->getUsername()) {
                if (runIsLocked($row['id'])) {
                    $lock = " [<a href=\"index.php?action=lockrun&id=$row[id]&state=unlock\">Unlock Run</a>] ";
                } else {
                    $lock = " [<a href=\"index.php?action=lockrun&id=$row[id]&state=lock\">Lock Run</a>] ";
                }
            }

            // IS in the run, give option to leave.
            $add = " [<a href=\"index.php?action=partrun&id=$ID\">Leave Op</a>] [<a href=\"index.php?action=cans\">Manage Cans</a>]";
            //$add .= " [Leave Op Disabled] [<a href=\"index.php?action=cans\">Manage Cans</a>]";

            // Make the charity button.
            $charityFlag = $DB->getCol("SELECT charity FROM joinups WHERE run='$ID' AND userid='" . $MySelf->getID() . "' AND parted IS NULL LIMIT 1");
            if ($charityFlag[0]) {
                $charity = " [<a href=\"index.php?action=toggleCharity&id=$ID\">Unset Charity Flag</a>]";
            } else {
                $charity = " [<a href=\"index.php?action=toggleCharity&id=$ID\">Set Charity Flag</a>]";
            }
        }
        // Give option to end this op.
        if (($MySelf->getID() == $row['supervisor']) || ($MySelf->canCloseRun() && ($MySelf->isOfficial() || runSupervisor($row['id']) == $MySelf->getUsername()))) {
            $add2 = " [<a href=\"index.php?action=endrun&id=$ID\">Close Op</a>]";
        }

        // Refresh button.
        $refresh_button = " [<a href=\"index.php?action=show&id=$row[id]\">Reload page</a>]";
        $general_info->addCol($join . $addHaul . $add2 . $lock . $add . $charity . $refresh_button);

    } else {
        // Mining run ended.

        // Row: Ended
        $general_info->addRow();
        $general_info->addCol("Ended on:", $common_mode);
        $general_info->addCol(date("d.m.y H:i", $row['endtime']));
        $ranForSecs = $row['endtime'] - $row['starttime'];

        // Duration
        $general_info->addRow();
        $general_info->addCol("Duration:", $common_mode);
        if ($ranForSecs < 0) {
            $general_info->addCol("Event was canceled before starttime.");
        } else {
            $general_info->addCol(numberToString($ranForSecs));
        }

        // Set flag for later that we dont generate active ship data.
        $DontShips = true;

        // Current TMEC
        $general_info->addRow();
        $general_info->addCol("TMEC reached:");
        $general_info->addCol(calcTMEC($row['id']), true);
    }

// We have to check for "0" - archiac runs that have no ore values glued to them

    if ($row['oreGlue'] > 0) {
        $general_info->addRow();
        $general_info->addCol("Ore Quotes:", $common_mode);

        // Is this the current ore quote?
        $cur = $DB->getCol("SELECT time FROM orevalues ORDER BY time DESC LIMIT 1");
        if (isset($cur[0]) && $cur[0] <= $row['oreGlue']) {
            // it is!
            $cur = "<font color=\"#00ff00\"><b>(current)</b></font>";
        } else {
            $cur = "<font color=\"#ff0000\"><b>(not using current quotes)</b></font>";
        }

        // Date of ore mod?
        //$modTime = $DB->getCol("SELECT time FROM orevalues WHERE time='" .  . "' LIMIT 1");
        $modDate = date("d.m.y", $row['oreGlue']);
        $general_info->addCol("[<a href=\"index.php?action=showorevalue&id=" . $row['oreGlue'] . "\">$modDate</a>] $cur");
    }
//Edit Starts Here
// We have to check for "0" - archiac runs that have no ship values glued to them

    if ($row['shipGlue'] > 0) {
        $general_info->addRow();
        $general_info->addCol("Ship Values:", $common_mode);

        // Are these the current Ship Values?
        $cur = $DB->getCol("SELECT id FROM shipvalues ORDER BY time DESC LIMIT 1");
        if ($cur[0] == $row['shipGlue']) {
            // it is!
            $cur = "<font color=\"#00ff00\"><b>(current)</b></font>";
        } else {
            $cur = "<font color=\"#ff0000\"><b>(not using current quotes)</b></font>";
        }

        // Date of ship mod?
        $modTime = $DB->getCol("SELECT time FROM shipvalues WHERE id='" . $row['shipGlue'] . "' LIMIT 1");
        $modDate = date("d.m.y", $modTime[0]);
        $general_info->addCol("[<a href=\"index.php?action=showshipvalue&id=" . $row['shipGlue'] . "\">$modDate</a>] $cur");
    }
//Edit Ends Here

	/*
	 * STEP THREE
	 * Create a table with the System Information.
	 */
    // This tries to load the corresponing EVE dataset for the system.
    $System = new solarSystem($row['location']);
    $System_table = $System->makeInfoTable() . "<br>";

	/*
	 * STEP FOUR
	 * The Join and Part log.
	 */
    // Row: Joinups.

// Are we the supervisor of this run and/or an official?
    if ((runSupervisor($ID) == $MySelf->getID()) || $MySelf->isOfficial()) {
        // We are.
        $join_info = new table(9, true);
        $icankick = true;
    } else {
        // We are not.
        $join_info = new table(6, true);
    }

    $join_info->addHeader(">> Active Pilots");

    if ($joinlog->numRows() > 0) {
        // Someone or more joined.

        $join_info->addRow("#060622");
        $join_info->addCol("Pilot", array (
            "bold" => true
        ));
        $join_info->addCol("Joined", array (
            "bold" => true
        ));
        $join_info->addCol("Active Time", array (
            "bold" => true
        ));
        $join_info->addCol("State", array (
            "bold" => true
        ));
        $join_info->addCol("Shiptype", array (
            "bold" => true
        ));
        $join_info->addCol("Charity", array (
            "bold" => true
        ));

        // Print the kick/ban/remove headers.
        if ($icankick) {
            $join_info->addCol("Remove", array (
                "bold" => true
            ));
            $join_info->addCol("Kick", array (
                "bold" => true
            ));
            $join_info->addCol("Ban", array (
                "bold" => true
            ));
        }

        // Loop through all users who joined up.
        $activePeople = 0;

        while ($alog = $activelog->fetchRow()) {

            // People counter
            $activePeople++;

            $join_info->addRow();
            $join_info->addCol(makeProfileLink($alog['userid']));

            if ($TIMEMARK < $alog['joined']) {
                $join_info->addCol("request pending");
            } else {
                $join_info->addCol(date("H:i:s", $alog['joined']));
            }

            $time = numberToString($TIMEMARK - $alog['joined']);
            if ($time) {
                $join_info->addCol($time);
                $join_info->addCol("<font color=\"#00ff00\">ACTIVE</font>");
            } else {
                $join_info->addCol("request pending");
                $join_info->addCol("<font color=\"#FFff00\">PENDING</font>");
            }
            $join_info->addCol($SHIPTYPES[$alog['shiptype']]);

            $join_info->addCol(yesno($alog['charity'], 1, 0));

            // Print the kick/ban/remove headers.
            if ($icankick) {
                if ($alog['userid'] == $MySelf->getID()) {
                    // Cant kick yourself.
                    $join_info->addCol("---");
                    $join_info->addCol("---");
                    $join_info->addCol("---");
                } else {
                    $join_info->addCol("[<a href=\"index.php?action=kickban&state=1&joinid=$alog[id]\">remove</a>]");
                    //Edit start to remove kick/leave op
                    $join_info->addCol("[<a href=\"index.php?action=kickban&state=2&joinid=$alog[id]\">kick</a>]");
                    //$join_info->addCol("[disabled]");
                    $join_info->addCol("[<a href=\"index.php?action=kickban&state=3&joinid=$alog[id]\">ban</a>]");
                    //$join_info->addCol("[disabled]");
                    //Edit End
                }
            }
        }

        // Tell the folks how many active pilots we have, switching none, one or many.
        switch($join_info){
            case("0"):
                $join_info->addHeader("There are no active pilots.");
                break;

            case("1"):
                $join_info->addHeader("There is one pilot.");
                break;

            default:
                $join_info->addHeader("There are " . $activePeople . " active pilots.");
                break;
        }


        /*
         * Show what ships are currently online.
         */
        if (!$DontShips) {
            $OnlineShips = $DB->query("SELECT count(shiptype) as count, shiptype FROM joinups WHERE run = '$ID' and parted is NULL GROUP BY shiptype");

            $shiptype_info = new table(2, true);
            $shiptype_info->addHeader(">> Active Ships");
            $shiptype_info->addRow("#060622");
            $shiptype_info->addCol("Shiptype", array (
                "bold" => true
            ));
            $shiptype_info->addCol("Active count", array (
                "bold" => true
            ));

            while ($ship_data = $OnlineShips->fetchRow()) {
                $shiptype = $ship_data[shiptype];
                $count = $ship_data[count];

                $shiptype_info->addRow();
                $shiptype_info->addCol($SHIPTYPES[$shiptype]);
                $shiptype_info->addCol($count . " active");
                $gotShips = true;
            }
        }

        /*
         * Now that we know that there was at least ONE user who is active we can
         * assemble a join and part log.
         */

        $partlog_info = new table(7, true);
        $partlog_info->addHeader(">> Attendance Log");
        $partlog_info->addRow("#080822");
        $partlog_info->addCol("Pilot", array (
            "bold" => true
        ));
        $partlog_info->addCol("Joined", array (
            "bold" => true
        ));
        $partlog_info->addCol("Parted", array (
            "bold" => true
        ));
        $partlog_info->addCol("Active Time", array (
            "bold" => true
        ));
        $partlog_info->addCol("State", array (
            "bold" => true
        ));
        $partlog_info->addCol("Shiptype", array (
            "bold" => true
        ));
        $partlog_info->addCol("Notes", array (
            "bold" => true
        ));

        while ($join = $joinlog->fetchRow()) {

            $partlog_info->addRow();
            $partlog_info->addCol(makeProfileLink($join[userid]));

            if ($TIMEMARK >= $join[joined]) {

                $partlog_info->addCol(date("H:i:s", $join[joined]));

                if ("$join[parted]" != "") {
                    $partlog_info->addCol(date("H:i:s", $join[parted]));
                    $partlog_info->addCol(numberToString((($join[parted] - $join[joined]))));
                    $partlog_info->addCol("<font color=\"#ff0000\">INACTIVE</font>");
                } else {
                    $partlog_info->addCol("<i>soon(tm)</i>");
                    $partlog_info->addCol(numberToString((($TIMEMARK - $join[joined]))));
                    $partlog_info->addCol("<font color=\"#00ff00\">ACTIVE</font>");
                }

                $partlog_info->addCol(joinAs($join[shiptype]));

            } else {
                $partlog_info->addCol("request pending");
                $partlog_info->addCol("request pending");
                $partlog_info->addCol("request pending");
                $partlog_info->addCol("request pending");
                $partlog_info->addCol(joinAs($join[shiptype]));
            }

            // Get the removal reason.
            switch ($join[status]) {
                default :
                case ("0") :
                    $reason = " ";
                    break;
                case ("1") :
                    $reason = "removed by " . ucfirst(idToUsername($join[remover]));
                    break;
                case ("2") :
                    $reason = "<font color=\"#ffff00\">kicked</font> by " . ucfirst(idToUsername($join[remover]));
                    break;
                case ("3") :
                    $reason = "<font color=\"#ff0000\">banned</font> by " . ucfirst(idToUsername($join[remover]));
                    break;
            }
            $partlog_info->addCol($reason);

        }

    }

	/*
	 * STEP FIVE
	 * The Resources Information Table
	 */
    $ressources_info = new table(5, true);
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
    $ressources_info->addCol("Value", array (
        "bold" => true
    ));
    $ressources_info->addCol("ISK", array (
        "bold" => true,
        "align" => "right"
    ));

// Load current payout values.

    // Voila, le scary monster!
    //$oval = $ovalues->fetchRow() AND
    $totalworth = $total_ore_m3 = 0;
    $oval = $ovalues;
    $gotOre = false;
    $r = $DB->query("select item, sum(Quantity) as total, itemName as name, itemID from hauled, itemList where item = friendlyName and miningrun = '$ID' group by item having sum(Quantity) <> 0");
    while($r2 = $r->fetchRow()){
        $ORE = $r2['item'];
        // We need a Variable name with the word Wanted and M3 (for the wanted and m3 columns)
        $OREWANTED = $ORE . "Wanted";
        //Pulls the m3 of each ore type.
        //$OREWORTH = getMarketPrice($r2['typeID']);
        $OREWORTH = ($oval[$ORE]["Worth"]);
        $OREM3 = $r2['volume'];

        /* If an ore is neither wanted nor has been harvested so far, we dont print
         * that row to save precious in game browser space.
         */

        if (($row[$ORE] != 0)) {

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

            $ressources_info->addRow();

            // Fetch the right image for the ore.
            $ri_words = str_word_count($r2['name'], 1);
            $ri_max = count($ri_words);
            $ri = strtolower($ri_words[$ri_max -1]);

            $ressources_info->addCol("<img width=\"32\" height=\"32\" src=\"http://image.eveonline.com/Type/" . $r2['itemID'] . "_32.png\">", array (
                "width" => "64"
            ));
            $ressources_info->addCol($r2['name'], array (
                "bold" => true
            ));

            $ressources_info->addCol($tmp_ore . " / " . $tmp_ore_m3);
            $ressources_info->addCol(number_format($OREWORTH) . " ISK");
            $ressources_info->addCol(number_format($worth, 2) . " ISK", array (
                "bold" => true,
                "align" => "right"
            ));

            $gotOre = true; // We set this so we know we have SOME ore.
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
        "colspan" => 4
    ));
    $ressources_info->addCol(number_format($taxes, 2) . " ISK", array (
        "align" => "right",
        "bold" => true
    ));

    $ressources_info->addRow("#060622");
    $ressources_info->addCol("Net value:", array (
        "bold" => true,
        "align" => "right",
        "colspan" => 4
    ));
    $ressources_info->addCol(number_format($net, 2) . " ISK", array (
        "align" => "right",
        "bold" => true
    ));

	/*
	 * STEP SIX
	 * Gather all cans that belong to this miningrun.
	 */

    if (getConfig("cargocontainer")) {
        $can_information = new table(8, true);
        $modes = array (
            "bold" => true
        );
        $can_information->addHeader(">> Cargo containers in space, belonging to this Op");

        if ($CansDS->numRows() > 0) {

            $TTL = getConfig("canLifeTime") * 60;
            // Table headers.
            $can_information->addRow("#060622");
            $can_information->addCol("Name of Can", $modes);
            $can_information->addCol("Owner of Can", $modes);
            $can_information->addCol("Location of Can", $modes);
            $can_information->addCol("Droptime", $modes);
            $can_information->addCol("est. Poptime", $modes);
            $can_information->addCol("Time left", $modes);
            $can_information->addCol("Can is full", $modes);

            // Are we allowed to pop cans?
            if ((userInRun($MySelf->getID(), $row[id]) && $MySelf->canAddHaul())) {
                $iCanPopCans = true;
            }

            // We are. Lets add a "pop can" column.
            if ($iCanPopCans) {
                $can_information->addCol("Pop can", $modes);
            } else {
                $can_information->addCol("", $modes);
            }

            // Loop through the cans.
            while ($can = $CansDS->fetchRow()) {

                // Calculate droptimes, poptimes et all.
                $candroptime = $can[droptime];
                $timeleft = ($candroptime + $TTL) - $TIMEMARK;
                $minsleft = str_pad((number_format((($timeleft -60) / 60), 0)), "2", "0", STR_PAD_LEFT);
                $secsleft = str_pad(($timeleft % 60), "2", "0", STR_PAD_LEFT);
                $poptime = $candroptime + $TTL;

                if ($secsleft < 1) {
                    $secsleft = "00";
                }

                if ($minsleft >= 30) {
                    $color = "#88ff88";
                }

                elseif ($minsleft < 29 && $minsleft >= 15) {
                    $color = "#FFFF00";
                }

                elseif ($minsleft < 15) {
                    $color = "#FF0000";
                }

                // Add the information to the table.
                $can_information->addRow();
                $can_information->addCol($can[name]);
                $can_information->addCol(ucfirst(idToUsername($can[pilot])));
                $system = new solarSystem($can[location]);
                $can_information->addCol(ucfirst($system->makeFancyLink()));
                $can_information->addCol(date("H:i:s", $can[droptime]));
                $can_information->addCol(date("H:i:s", $poptime));

                // Can popped already?
                if ($minsleft > 0) {
                    $can_information->addCol("<font color=\"$color\">" . $minsleft . ":" . $secsleft . "</font>");
                } else {
                    $can_information->addCol("<font color=\"$color\">POPPED</font>");
                }

                // Can full?
                if ($can[isFull]) {
                    $can_information->addCol("<font color=\"#00ff00\">YES</font>");
                } else {
                    $can_information->addCol("No");
                }

                // Offer a pop-can button if we are allowed to do so.
                if ($iCanPopCans) {
                    $can_information->addCol("[<a href=\"index.php?action=popcan&id=$can[id]&runid=$row[id]\">pop</a>]");
                } else {
                    $can_information->addCol("");
                }
            }
        } else {
            $CANS_run = false;
            $can_information->addRow("#060622");
            $can_information->addCol("There are Currently No Active Cans Assigned to this Op!", array (
                "bold" => true,
                "colspan" => 8,
                "align" => "center"
            ));
        }
    }

	/*
	 * STEP SEVEN
	 * Show the transport manifest	 
	 */
    // Are there any hauls at all?
    if (getTotalHaulRuns($ID) > 0) {

        // Ask the oracle.
        if ($_GET[detailed] != true){
            $limit = "6";
        }

        $haulingDB = $DB->query("select * from hauled where miningrun = '$ID' group by time ORDER BY time DESC");

        // Create the table header.
        $hauled_information = new table(4, true);
        $hauled_information->addHeader(">> Transport Manifest");
        $hauled_information->addRow("#060622");
        $hauled_information->addCol("Hauler", array (
            "bold" => true
        ));
        $hauled_information->addCol("Time", array (
            "bold" => true
        ));
        $hauled_information->addCol("Location", array (
            "bold" => true
        ));
        $hauled_information->addCol("Freight", array (
            "bold" => true
        ));

        // Delete uneeded vars.
        unset ($temp);

        // Lets loop through the results!
        while ($row = $haulingDB->fetchRow()) {

            // The who hauled to where when stuff.
            $hauled_information->addRow(false, top);
            $hauled_information->addCol(makeProfileLink($row[hauler]));
            $hauled_information->addCol(date("H:i:s", $row[time]));
            $system = new solarSystem($row[location]);
            $hauled_information->addCol(ucfirst($system->makeFancyLink()));

            /*
             * Now we loop through all the ore in the hauled database (result)
             * and print a Oretype: Amount for each Oretype that has an amount
             * greater or lesser than zero, but not zero.
             */
            $temp = "";
            $oc = 1;
            $singleHaulDB = $DB->query("select Item, Quantity, itemName as typeName from hauled h, itemList i where h.item = friendlyName and miningrun = '$ID' and time = $row[time] ORDER BY Item");
            while ($haul = $singleHaulDB->fetchRow()) {
                $ORE = $haul['Item'];

                if ($haul['Quantity'] > 0) {
                    $temp .= number_format($haul['Quantity'], 0) . " " . $haul['typeName'] . "<br>";
                }
                elseif ($haul['Quantity']) {
                    // Negative amount (storno)
                    $temp .= "<font color=\"#ff0000\">" . number_format($haul['Quantity'], 0) . " " . $haul['typeName'] . "</font><br>";
                }

            }
            $oc++;
            $hauled_information->addCol($temp);
            unset ($temp);
            if($oc > $limit)
                break;
        }



        // offer full view.
        if ($limit) {
            $hauled_information->addHeader("Only the 6 most recent hauls are shown. [<a href=\"index.php?action=show&id=".$ID."&detailed=true\">show all<a>]");
        } else {
            $hauled_information->addHeader("All hauls are shown. [<a href=\"index.php?action=show&id=".$ID."\">show only recent<a>]");
        }
    }

	/*
	 * STEP EIGHT
	 * Calculate the payout.
	 */
    // Calculate Payout:

    $joinedPeople = $DB->query("SELECT DISTINCT userid FROM joinups WHERE run ='$ID' AND status < '2'");
    $ISK = getTotalWorth($ID, true);

    if ($ISK != 0) {

        $payout_info = new table(3, true);
        $payout_info->addHeader(">> Payout Information");
        $payout_info->addRow("060622");
        $payout_info->addCol("Pilot", array (
            "bold" => true
        ));
        $payout_info->addCol("Percent of Net", array (
            "bold" => true
        ));
        $payout_info->addCol("Payout", array (
            "bold" => true
        ));

        // get the payout array. Fun guaranteed.
        while ($peep = $joinedPeople->fetchRow()) {
            $payoutArray[idToUsername($peep[userid])] = calcPayoutPercent($ID, $peep[userid]);
        }

        // Calulate the percent-modifier.
        $percentModifier = 100 / array_sum($payoutArray);

        // Apply the modifier to the percentage.
        $names = array_keys($payoutArray);
        foreach ($names as $name) {
            $percent = $payoutArray[$name] * $percentModifier;

            $payout = ($ISK / 100) * $percent;
            $payout_info->addRow();

            $payout_info->addCol(makeProfileLink(usernameToID($name)));
            $payout_info->addCol(number_format($percent, 2) . "%");

            if($MySelf->isAccountant()){
                $payout_info->addCol("<a href=\"index.php?action=showTransactions&id=".usernameToID($name)."\">".number_format($payout, 2) . " ISK</a>");
            } else {
                $payout_info->addCol(number_format($payout, 2) . " ISK");
            }
            $totalPayout = $totalPayout + $payout;
            $totalPercent = $totalPercent + $percent;
        }

        $payout_info->addRow("060622");
        $payout_info->addCol("Player Total", array (
            "bold" => true
        ));
        $payout_info->addCol(number_format($totalPercent, 2) . "%");
        $payout_info->addCol(number_format($totalPayout, 2) . " ISK");
    }
	
	/*
	 * STEP NINE
	 * Calculate the Material Conversion from a Perfect Refine.
	 */
	//include ('./functions/runs/listRun_inc_step9.php');

	/*
	 * Assemble & Return the HTML
	 */
	$page = "<h2>Detailed mining run information</h2>";

	$page .= $System_table;

	$isOpen = miningRunOpen($ID);

	if ($general_info->hasContent()) {
		$page .= $general_info->flush();
	}

	if ($isOpen) {
		if ($activePeople > 0) {
			$page .= "<br>" . $join_info->flush();
		} else {
			$page .= "<br><b><i>There are currently no active pilots.</i></b><br>";
		}

		if ($gotShips) {
			$page .= "<br>" . $shiptype_info->flush();
		}
	}

	if ($ISK != 0) {
		$page .= "<br>" . $payout_info->flush();
	}

	if (isset ($partlog_info) && $partlog_info->hasContent()) {
		$page .= "<br>" . $partlog_info->flush();
	} else {
		$page .= "<b><i>No one ever joined or left this operation.</i></b><br>";
	}

	if ($gotOre) {
		$page .= "<br>" . $ressources_info->flush();
	} else {
		$page .= "<b><i>Nothing has been mined (and hauled) yet.</i></b><br>";
	}
	
	/*
	if (isset ($conversion_info) && $conversion_info->hasContent()) {
		$page .= "<br>" . $conversion_info->flush();
	} else {
		$page .= "<b><i>There are not records of any hauling.</i></b><br>";
	}
	*/
	
	if (getConfig("cargocontainer")) {
		if ($isOpen) {
			if (isset ($can_information) && $can_information->hasContent()) {
				$page .= "<br>" . $can_information->flush();
			} else {
				$page .= "<b><i>There are no cans out there that belong to this mining operation.</i></b><br>";
			}
		}
	}

	if (isset ($hauled_information) && $hauled_information->hasContent()) {
		$page .= "<br>" . $hauled_information->flush();
	} else {
		$page .= "<b><i>There are not records of any hauling.</i></b><br>";
	}
	
	return ($page);
}
?>

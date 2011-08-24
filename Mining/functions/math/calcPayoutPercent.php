<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/calcPayoutPercent.php,v 1.14 2008/01/02 20:01:33 mining Exp $
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
 * This is a scary little bit*h. 
 * Because it has to do with math.
 * Math is evil.
 * No one likes math.
 */

function calcPayoutPercent($run, $pilot) {

	// Sanity check.
	numericCheck($run, 0);
	numericCheck($pilot, 0);

	// Globals.
	global $DB;
	global $TIMEMARK;

	// Lets get the total jointime.
	$joinTimes = $DB->query("SELECT * FROM joinups WHERE userid='$pilot' AND run='$run'");

	// We got any results? If not, that pilot never attended!
	if ($joinTimes->numRows() == 0) {
		return (0);
	}

	// Assemble the time.
	while ($joinTime = $joinTimes->fetchRow()) {
		// Joinup time.
		$joinup = $joinTime[joined];

		// Part time., handle still-active folks.
		if ($joinTime[parted] > 0) {
			// Pilot left.
			$left = $joinTime[parted];
		} else {
			// Pilot still active: Set current time as part-time.
			$left = $TIMEMARK;
		}

		// Add his active seconds to a batch.
		$totalSeconds = $totalSeconds + ($left - $joinup);
	}

	// Get the run's start and endtime.
	$Run_DS = $DB->query("SELECT starttime, endtime FROM runs WHERE id='$run' LIMIT 1");
	$Run = $Run_DS->fetchRow();

	// Endtime, handle still-open cases.
	$run_starttime = $Run[starttime];
	if ($Run[endtime] > 0) {
		$run_endtime = $Run[endtime];
	} else {
		$run_endtime = $TIMEMARK;
	}
	
	// runSeconds is the total number of seconds the run is open.
	$runSeconds = $run_endtime - $run_starttime;
	$timePercent = 100 / ($runSeconds / $totalSeconds);

	// How many people joined this run?
	$totalPilots = $DB->getCol("SELECT COUNT(DISTINCT userid) FROM joinups AS count WHERE run='$run'");
	$totalPilots = $totalPilots[0];
	
//Edit Starts Here	
		// How many Ships Joined by Type
		$totalShipType = $DB->getCol("SELECT COUNT(DISTINCT shiptype) FROM joinups AS count WHERE run='$run'");
		$totalShipType = $totalShipType[0];
		
		$userShipTypeA = $DB->getCol("SELECT shiptype FROM joinups WHERE run='$run' AND userid='$pilot' AND (parted is null OR status < 2)");
		$shipGlue = $DB->getCol("SELECT shipGlue FROM runs WHERE id='$run' LIMIT 1");
		$userShipType = $userShipTypeA[0];
		$shipglue = $shipGlue[0];
		
		switch($userShipType){
			case "0":
				$ShipTypeValueA = $DB->getCol("SELECT AssaultShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueA[0];
				break;
			case "1":
				$ShipTypeValueB = $DB->getCol("SELECT BattlecruiserValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueB[0];
				break;
			case "2":
				$ShipTypeValueC = $DB->getCol("SELECT BattleshipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueC[0];
				break;
			case "3":
				$ShipTypeValueD = $DB->getCol("SELECT CarrierValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueD[0];
				break;
			case "4":
				$ShipTypeValueE = $DB->getCol("SELECT CommandShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueE[0];
				break;
			case "5":
				$ShipTypeValueF = $DB->getCol("SELECT CovertOpsValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueF[0];
				break;
			case "6":
				$ShipTypeValueG = $DB->getCol("SELECT CruiserValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueG[0];
				break;
			case "7":
				$ShipTypeValueH = $DB->getCol("SELECT DestroyerValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueH[0];
				break;
			case "8":
				$ShipTypeValueI = $DB->getCol("SELECT DreadnoughtValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueI[0];
				break;
			case "9":
				$ShipTypeValueJ = $DB->getCol("SELECT ExhumerValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValueJ[0];
				break;
			case "10":
				$ShipTypeValue = $DB->getCol("SELECT FreighterValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "11":
				$ShipTypeValue = $DB->getCol("SELECT FrigateValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "12":
				$ShipTypeValue = $DB->getCol("SELECT HeavyAssaultShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "13":
				$ShipTypeValue = $DB->getCol("SELECT IndustrialShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "14":
				$ShipTypeValue = $DB->getCol("SELECT InterceptorValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "15":
				$ShipTypeValue = $DB->getCol("SELECT InterdictorValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "16":
				$ShipTypeValue = $DB->getCol("SELECT LogisticsShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "17":
				$ShipTypeValue = $DB->getCol("SELECT MiningBargeValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "18":
				$ShipTypeValue = $DB->getCol("SELECT ReconShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "19":
				$ShipTypeValue = $DB->getCol("SELECT ShuttleValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "20":
				$ShipTypeValue = $DB->getCol("SELECT TransportShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "21":
				$ShipTypeValue = $DB->getCol("SELECT CapitalIndustrialShipValue FROM shipvalues WHERE id='$shipglue'");
				$ShipTypeValue = $ShipTypeValue[0];
				break;
			case "99":
			default:
				$ShipTypeValue = "0";
				break;
		}
		
//Edit Ends Here
	
	$myPart1 = ((100 / $totalPilots) * ($timePercent / 100)) * $ShipTypeValue;
	$myPart = $myPart - $myPart1;

	//Below is used for debuging only do not uncomment unless you know what you are doing.
	//Start Debug
	//echo $totalPilots;
	//echo $timePercent;
	//echo $myPart1;
	//echo "<br>";
	//echo $ShipTypeValue;
	//echo "<br>";
	//echo $userShipType;
	//End Debug
	
	// Return the Percentage.
	return ($myPart);
}
?>
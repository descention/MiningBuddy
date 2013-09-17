<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/getTotalWorth.php,v 1.12 2008/01/02 20:01:33 mining Exp $
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
 * This function takes an int and queries the database.
 * It returns the total worth of isk for that mining run.
 */

function getTotalWorth($id, $net = false) {
	// First, we need the globals
	global $DB;
	global $ORENAMES;
	global $DBORE;
	global $SHIPTYPES;
	global $DBSHIP;

	// Is $id truly an integer?
	numericCheck($id);
    $value = 0;
	// we need some results.
	$select = "";
	$r = $DB->query("select item, sum(Quantity) as total from hauled where miningrun = '$id' group by item having sum(Quantity) <> 0");
	while($r2 = $r->fetchRow()){
		if($r2['total'] != 0){
			$select .= ", '".$r2['total']."' as ".$r2['item'];
		}
	}

	$runs = $DB->query("SELECT id,oreGlue $select FROM runs WHERE id = '$id' limit 1");

	// And check that we actually suceeded.
	if ($runs->numRows() != 1) {
		makeNotice("Internal error: SELECT id,oreGlue $select FROM runs WHERE id = '$id' limit 1", "error", "Internal Error!");
	} else {
		$run = $runs->fetchRow();
	}
	
	if ($runs->numRows() != 1) {
		makeNotice("Specified run not found, or does no longer exist!", "warning", "Internal Error");
	}

	// Load the appropiate ore values.
	if ($run['oreGlue'] <= 0) {
		$oreValueR = $DB->query("select * from orevalues a where time = (select max(time) from orevalues b where a.item = b.item) group by item ORDER BY time DESC");
	} else {
		$oreValueR = $DB->query("select * from orevalues a where time = (select max(time) from orevalues b where a.item = b.item and time <= (select oreglue from runs where id = '$id')) group by item ORDER BY time DESC");
	}

	// Create variables according to ore names, fill them with price info.
	$value = 0;
	while ($row = $oreValueR->fetchRow()) {
		$oreValue[$row['Item']] = $row['Worth'];
		if(isset($run[$row['Item']]))
			$value += $run[$row['Item']] * $row['Worth'];
	}
	/*
	// Now multiply each ore amount with raw value, add it to total value.
	foreach ($DBORE as $ORE) {
		if(isset($oreValue[$ORE])){
			$value = $value + ($run[$ORE] * $oreValue[$ORE]);
		}
	}
	*/

	/*
//Edit Starts Here	
	// Load the appropiate ship values.
	if ($run['shipGlue'] <= 0) {
		$shipvalues = $DB->query("select * from shipvalues order by id desc limit 1");
	} else {
		$shipvalues = $DB->query("select * from shipvalues where id='" . $run['shipGlue'] . "' limit 1");
	}
	$row = $shipvalues->fetchRow();

	// Create variables according to ship names, fill them with price info.
	foreach ($DBSHIP as $SHIP) {
		$shipValue[$SHIP] = $row[$SHIP . Value];
	}

	// Now multiply each ship amount with raw value, and subtract it from total value.
	foreach ($DBSHIP as $SHIP) {
		$svalue = $svalue - ($run[$SHIP] * $shipValue[$SHIP]);
	}
//Edit Ends Here, but continues.
	*/
	// Deduct corp tax.
	if ($net) {
		$CorpTax = $DB->getCol("SELECT corpkeeps FROM runs WHERE id='$id'");
		$taxes = ($value * $CorpTax[0]) / 100;
		$value = $value - $taxes;
	}

    return $value;
}
?>

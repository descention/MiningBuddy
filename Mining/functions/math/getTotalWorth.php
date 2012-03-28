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
	
	$runs = $DB->query("SELECT id FROM runs WHERE id = '$id' limit 1");
	
	if ($runs->numRows() != 1) {
		makeNotice("Specified run not found, or does no longer exist!", "warning", "Internal Error");
	}
	
	// we need some results.
	$r = $DB->query("select sum(Quantity) as total, typeID from hauled, evedump.invTypes where item = replace(replace(typeName,' ',''),'-','') and miningrun = '$id' group by item");
	while($r2 = $r->fetchRow()){
		if($r2[total] != 0){
			$value += ($r2[total] * getMarketPrice($r2[typeID]));
		}
	}
	
	// Deduct corp tax.
	if ($net) {
		$CorpTax = $DB->getCol("SELECT corpkeeps FROM runs WHERE id='$id'");
		$taxes = abs($value * $CorpTax[0]) / 100;
		$value = $value - $taxes;
	}
//Edit Continues Here
	// Deduct Ship Values
	
//Edit Ends Here
	return ($value);
}
?>
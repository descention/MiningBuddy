<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/runs/listRun_inc_step1.php,v 1.13 2008/01/12 15:53:12 mining Exp $
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
 * Inside:
 * Database business ONLY
 */

// We have to SELECT the most fitting ID. This can be done in three ways.
if (("$_GET[id]" >= 0) && (is_numeric($_GET['id']))) {
	// Way Nr. 1: The user specified an ID.
	$ID = $_GET['id'];
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
		$select .= ", '$r2[total]' as $r2[item]";
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
if (!isset($row['m3Glue']) || $row['m3Glue'] <= 0) {
	$mvalues = $DB->query("SELECT * FROM m3values order by id desc limit 1");
} else {
	$mvalues = $DB->query("SELECT * FROM m3values WHERE id='" . $row['m3Glue'] . "' limit 1");
}
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
if (!isset($row['matGlue']) || $row['matGlue'] <= 0) {
	$matvalues = $DB->query("SELECT * FROM materials1 order by type desc limit 1");
} else {
	$matvalues = $DB->query("SELECT * FROM materials1 WHERE type='" . $MAT . "' limit 1");
}




// Load cargo container database.
if (getConfig("cargocontainer")) {
	$CansDS = $DB->query("SELECT id, location, droptime, name, pilot, isFull, miningrun FROM cans WHERE miningrun='$ID' ORDER BY droptime ASC");
}

// note: hauling DB queries have been move into the according step-file
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/math/numberToString.php,v 1.9 2008/01/04 11:39:01 mining Exp $
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

function numberToString($id) {

	// We need a number. GOE0.
	if (!number_format($id, 0)) {
		$BT = nl2br(print_r(debug_backtrace(), true));		
		makeNotice("Thats not a real timeindex in numberToString!<br><br>$BT", "warning", "Err..");
	}

	if ($id < 0) {
		return (false);
	}

	if ($id >= 86400) {
		$days = floor($id / 86400);
		$thingies++;
	}

	if ($id >= 3600) {
		$hours = floor(($id % 86400) / 3600);
		$thingies++;
	}

	if ($id >= 60) {
		$minutes = floor((($id % 86400) % 3600) / 60);
		$thingies++;
	}

	$seconds = number_format(((($id % 86400) % 3600) % 60), 0);

	if ($days > 1) {
		$days = "$days days";
	}
	elseif ($days == 1) {
		$days = "$days day";
	}

	if ($hours > 1) {
		$hours = "$hours hours";
	}
	elseif ($hours == 1) {
		$hours = "$hours hour";
	}
	elseif ($hours == 0 && $days) {
		$hours = "0 hours";
	}

	if ($minutes > 1) {
		$minutes = "$minutes minutes";
	}
	elseif ($minutes == 1) {
		$minutes = "$minutes minute";
	}
	elseif ($minutes == 0 && $hours) {
		$minutes = "0 minutes";
	}

	if ($seconds > 1) {
		$seconds = "$seconds seconds";
	}
	elseif ($seconds == 1) {
		$seconds = "$seconds second";
	}
	elseif ($seconds == 0 && $minutes) {
		$seconds = "0 seconds";
	}

	if ($days) {
		$string .= $days . numberToString_internal($thingies);
		$thingies--;
	}

	if ($hours) {
		$string .= $hours . numberToString_internal($thingies);
		$thingies--;
	}

	if ($minutes) {
		$string .= $minutes . numberToString_internal($thingies);
		$thingies--;
	}

	if ($seconds) {
		$string .= $seconds . numberToString_internal($thingies);
		$thingies--;
	}

	return ($string);

}

function numberToString_internal($thingies) {

	switch ($thingies) {
		case (0) :
			$string .= ".";
			break;

		case (1) :
			$string .= " and ";
			break;

		default :
			$string .= ", ";
			break;
	}
	return ($string);

}
?>
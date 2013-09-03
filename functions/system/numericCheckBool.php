<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/system/numericCheckBool.php,v 1.1 2008/01/03 14:55:11 mining Exp $
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

// Checks if the given (int)? is an int.

function numericCheckBool($num, $min = false, $max = false) {

	// Is the number numeric?
	if (!is_numeric($num)) {
		$BT = nl2br(print_r(debug_backtrace(), true));
		makeNotice("Security related abortion.<br>\"$num\" is not an integer, but rather of type " . gettype($num) . ".<br><br><b>Backtrace:<br>$BT", "error");
	}

	// Do we want to check against specific minimal and maximal values?
	if (is_numeric($min) && is_numeric($max)) {
		// We do! Compare.
		if (($num >= $min) && ($num <= $max)) {
			return (true);
		} else {
			return (false);
		}
	}
	
	// Compare only to a min value
	if (is_numeric($min) && !is_numeric($max)){
		if ($num >= $min) {
			return (true);
		} else {
			return (false);
		}
	}
	
    // only check for numeric. But we did that earlier, sooo....
    return (true);
	
}
?>
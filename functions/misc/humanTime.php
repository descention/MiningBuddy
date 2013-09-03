<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/misc/humanTime.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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

function humanTime($mode, $playdoo = false) {

	/*
	 * Mode is either toUnix or toHuman.
	 * toUnix converts the given array to an UNIX timestamp,.
	 * toHuman returns an array with split up time.
	 */

	switch ($mode) {

		case ("toUnix") :
			// To convert something back, we need an array.
			if (!is_array($playdoo)) {
				makeNotice("Internal Error: given argument is not an array in humanTime.", "error", "Internal Error");
			}

			// Check for validity.
			numericCheck($playdoo[day]);
			numericCheck($playdoo[month]);
			numericCheck($playdoo[year]);
			numericCheck($playdoo[hour]);
			numericCheck($playdoo[minute]); 

			// Assemble the time.
			$humantime = $playdoo[day] . "." . $playdoo[month] . "." . $playdoo[year] . " " . $playdoo[hour] . ":" . $playdoo[minute];

			// Convert it.
			$timestamp = date("U", strtotime($humantime));

			// Check and return.
			if ($timestamp >= 0) {
				// Its greater of equal zero, so we were successful.
				return ($timestamp);
			} else {
				// Ugh, something did not go right. False, FALSE!
				return (false);
			}
			break;

		case ("toHuman") :

			// We need a VALID timestamp.
			numericCheck($playdoo, 0);

			// Assemble and return.
			return (array (
				"day" => date("d",
				$playdoo
			), "month" => date("m", $playdoo), "year" => date("Y", $playdoo), "hour" => date("H", $playdoo), "minute" => date("i", $playdoo)));
			break;
	}
}
?>
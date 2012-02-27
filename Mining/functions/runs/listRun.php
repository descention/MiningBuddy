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
	 * Load the database row into $row. This requires us to look up the minigrun ID
	 * first.
	 */
	include ('./functions/runs/listRun_inc_step1.php');

	/*
	 * STEP TWO
	 * Gather some vital information.
	 */
	include ('./functions/runs/listRun_inc_step2.php');

	/*
	 * STEP THREE
	 * Create a table with the System Information.
	 */
	include ('./functions/runs/listRun_inc_step3.php');

	/*
	 * STEP FOUR
	 * The Join and Part log.
	 */
	include ('./functions/runs/listRun_inc_step4.php');

	/*
	 * STEP FIVE
	 * The Resources Information Table
	 */
	include ('./functions/runs/listRun_inc_step5.php');

	/*
	 * STEP SIX
	 * Gather all cans that belong to this miningrun.
	 */
	include ('./functions/runs/listRun_inc_step6.php');

	/*
	 * STEP SEVEN
	 * Show the transport manifest	 
	 */
	include ('./functions/runs/listRun_inc_step7.php');

	/*
	 * STEP EIGHT
	 * Calculate the payout.
	 */
	include ('./functions/runs/listRun_inc_step8.php');
	
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
		if ($gotActivePeople) {
			$page .= "<br>" . $join_info->flush();
		} else {
			$page .= "<br><b><i>There are currently no active pilots.</i></b><br>";
		}

		if ($gotShips) {
			$page .= "<br>" . $shiptype_info->flush();
		}
	}

	if ($ISK > 0) {
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
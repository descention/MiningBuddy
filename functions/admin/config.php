<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/setConfig.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * This function reads a value from the database or the session (cached)
 * Its used to quickly get the info we need.
 */

function getConfig($var, $forceFresh = false) {

	// Globals! Yay!
	global $DB;

	// Check that we have a descriptor.
	if ($var == "") {
		makeNotice("Invalid descriptor in getConfig!", "error", "internal Error!");
	}

	// Sanitize it.
	$var = sanitize($var);

	// Check if the value has been cached, unless forced.
	if (!$forceFresh) {
		if (isset ($_SESSION["config_$var"])) {
			return ($_SESSION["config_$var"]);
		}
	}

	// Not cached, get from DB.
	$setting = $DB->getCol("SELECT value FROM config WHERE name='$var' LIMIT 1");
	if(isset($setting[0])){
		// Cache it.
		$_SESSION["config_$var"] = $setting[0];

		// And return it.
		return($setting[0]);
	}else{
		return "";
	}

}
 
/*
 * This function writes a value to the database.
 */

function setConfig($var, $val) {

	// Globals! Yay!
	global $DB;

	// Check that we have a descriptor.
	if ($var == "") {
		makeNotice("Invalid descriptor in setConfig!", "error", "internal Error!");
	}
	
	// Sanitize it.
	$var = sanitize($var);
	$val = sanitize($val);

	// Do we have a valid config entry?
	$setting = $DB->query("DELETE FROM config WHERE name='".$var."' LIMIT 1");

	// Cache it.
	$setting = $DB->query("INSERT INTO config (name, value) VALUES (?,?)",
	                array("$var", "$val"));
	                
	if ($DB->affectedRows() != 1) {
		makeNotice("Could not update the database registry (setConfig)!", "error", "Internal error!");
	}

}
?>

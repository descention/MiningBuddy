<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/checkBan.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
  * This is a small dynamic login blocker if someone does too many attempts
  * to login.
  */
 
 function checkBan(){
 	
 	// We need the database and the current time.
 	global $DB;
 	global $TIMEMARK;
 	
 	// Get the ban time, the maximum attempts etc..
 	$maxAllowedPerIP = getConfig("banAttempts");
 	$banTime = getConfig("banTime") * 60;
 	$timeFrame = 1800;
 	$targetTimeStamp = $TIMEMARK - $timeFrame;
 	
 	// Enforce minimal settings.
 	if ($maxAllowedPerIP < 5) {
 		setConfig("banAttempts", "5");
 		$maxAllowedPerIP = 5;
 	} 
 	
 	if ($banTime < 60) {
 		setConfig("banTime", "1");
 		$banTime = 60;
 	}
 	
 	// Wash the username and IP. 
 	$username = strtolower(sanitize($_POST[username]));
	$ip = $_SERVER[REMOTE_ADDR];
	
	// Load counts from database.
	$attemptsIP = $DB->getCol("SELECT COUNT(incident) FROM failed_logins WHERE ip='".$ip."' AND failed_logins.time > ".$targetTimeStamp);
	$attemptsUsername = $DB->getCol("SELECT COUNT(incident) FROM failed_logins WHERE username='".$username."' AND failed_logins.time > ".$targetTimeStamp);
	
	// Deny access if limits reached.
	if (($attemptsIP[0] > $maxAllowedPerIP)|| ($attemptsUsername[0] > $maxAllowedPerIP)){
		// Get the time of the latest attempt.
		$latestAttempt = $DB->getCol("SELECT time FROM failed_logins WHERE ip='".$ip."' OR username='".$username."' ORDER BY time DESC LIMIT 1");
		
		// Lets check if that is still in the baaaaad area.
		if (($latestAttempt[0] + $banTime) > $TIMEMARK) {
			// Still banned.
			$remain = numberToString($latestAttempt[0] + $banTime - $TIMEMARK);
			makeNotice("You have reached the maximum number of login attempts. You are temporarily banned for " . (number_format($banTime/60, 0)) . " minutes. Time left on ban: " . $remain, "error", "Banned");
		} 
	}
	
	// If we get here, all is good.
	
 }
 
 ?>
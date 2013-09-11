<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeWelcome.php,v 1.46 2008/09/08 09:04:17 mining Exp $
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

// renders the welcome page, printed just after successfull login.
function makeWelcome() {
	// Load the globals.
	global $VERSION;
	global $SITENAME;
	global $IGB;
	global $IGB_VISUAL;
	global $MySelf;
	global $DB;
	global $ValidUntil;

	/* HTML stuff */
	$page = "<h2>Welcome to $VERSION!</h2>";

	/* The welcome table */
	$table = new table(2, true);
	$table->addHeader(">> Welcome to $VERSION");

	$table->addRow();
	$table->addCol("Logged in as:", array (
		"bold" => true,
		"align" => "right"
	));
	$table->addCol(ucfirst($MySelf->getUsername()));

	$table->addRow();
	$table->addCol("Registered Rank:", array (
		"bold" => true,
		"align" => "right"
	));
	$table->addCol($MySelf->getRankName());

	$table->addRow();
	$table->addCol("Latest login:", array (
		"bold" => true,
		"align" => "right"
	));
	if ($MySelf->getLastlogin() < 1) {
		$table->addCol("This is your very first login! Welcome!");
	} else {
		$table->addCol(date("r", $MySelf->getLastlogin()));
	}

	$table->addRow();
	$table->addCol("Your account:", array (
		"bold" => true,
		"align" => "right"
	));
	$table->addCol(number_format(getCredits($MySelf->getID()), 2) . " ISK");
	
	$table->addRow();
	$table->addCol("Your profile:", array (
		"bold" => true,
		"align" => "right"
	));
	$table->addCol(makeProfileLink($MySelf->getID()));

	global $BLESSED;
	if ($BLESSED) {
		$table->addRow("#330000");
		$table->addCol("Installation Blessed!", array (
			"bold" => true,
			"align" => "right",
			
		));
		$table->addCol("It is not affected by expiration. It runs with the highest priority on the server and all limitations have been lifted.");
	}
    
    // Set the file name to the announce text file.
    $announceFile = getConfig("announceFile");
    // Check its existence...
    if (isset($announceFile) && file_exists($announceFile)){
		// Then load it.
		$globalAnnounce = file_get_contents($announceFile);
		// Create announcement table...
		$announceTable = new table(1, true);
		$announceTable->addHeader(">>> Important hosting information");
		$announceTable->addRow();
		$announceTable->addCol("$globalAnnounce");
		// ... and add it to the page.
		$page .= $announceTable->flush();
	}
  
	$page .= $table->flush();
	
	/* Show failed Logins to admins. */
	if ($MySelf->isAdmin()) {
		$page .= showFailedLogins("15");
	} else {
		$page .= showFailedLogins("10", $MySelf->getUsername());
	}

	/* permissions table */
	$permsTable = new table(1, true);
	$permsTable->addHeader(">> Your permissions");

	// Permissions matrix
	$perms = array (
		"canLogin" => "log in.",
		"canJoinRun" => "join mining operations.",
		"canCreateRun" => "create new mining operations.",
		"canCloseRun" => "close mining operations.",
		"canDeleteRun" => "delete mining operations.",
		"canAddHaul" => "haul to mining operations.",
		"canSeeEvents" => "view scheduled events.",
		"canEditEvents" => "add and delete scheduled events.",
		"canChangePwd" => "change your own password.",
		"canChangeEmail" => "change your own email.",
		"canChangeOre" => "manage ore prices and enable/disable them.",
		"canAddUser" => "add new accounts.",
		"canSeeUsers" => "see other accounts.",
		"canDeleteUser" => "delete other accounts.",
		"canEditRank" => "edit other peoples ranks.",
		"canManageUser" => "grant and take permissions.",
		"isAccountant" => "manage the corporation wallet and authorize payments.",
		"isOfficial" => "create official mining runs (with payout).",
	);

	$permDS = $DB->getAssoc("SELECT * FROM users WHERE id='" . $MySelf->getID() . "' AND deleted='0'");

	$keys = array_keys($perms);
	foreach ($keys as $key) {
		if ($permDS[$MySelf->getID()][$key] == 1) {
			$permsTable->addRow();
			$permsTable->addCol("You are allowed to " . $perms[$key]);
		}
	}
	$permsTable->addHeader("If you believe your permissions are faulty, consult your CEO immediatly.");

	// Show the balance
	$balance = getTransactions($MySelf->getID());
	$logins = getLogins($MySelf->getID());
	$page .= "<br>" . $balance . "<br>" . $permsTable->flush() . "<br>" . $logins;

	// .. then return it.
	return ($page);
}
?>
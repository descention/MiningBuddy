<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/maintenance.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * This function offer some maintenance work for the admin.
 * Mostly database related truncates. 
 */
 
function maintenance() {

	// We need global stuff.
	global $MySelf;
	global $DB;
	
	// Are we allowed to be here?
 	if (!$MySelf->isAdmin()) {
 		// I think not!
 		makeNotice("You Are not allowed to do this. CONCORD has been informed.", "warning", "Warp scrambled");
 	}

	// Check for wipe request
	if ($_GET[wipe]) {
		// Get user confirmation on database expunge!		
		confirm("Are you sure you want to expunge the database?");

		// Now lets check what sort of truncation the admin wants.
		switch ("$_GET[wipe]") {

			// Images, the most tough part.
			case ("images") :
				
				// We need the URL we run at.
				global $DOMAIN;
				
				// Then we have a look at the cached directory tree.
				$dir = opendir("./images/cache/". $DOMAIN . "/");
				
				// lets loop through each file...
				while ($file = readdir($dir)) {

					// ... that matched *.png ...
					if (preg_match("/.png/", $file)) {

						// ... and delete it!
						unlink("./images/cache/". $DOMAIN . "/" . $file);
					}
				}
				
				// Now wipe the database clean.
				$DB->query("TRUNCATE images;");
				
				// Inform the user.
				makeNotice("The cached images have been deleted.", "warning", "Maintenance Result", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;

			// Lets wipe some cans.
			case ("cans") :
				// Get down to business, clean the DB!
				$DB->query("TRUNCATE cans;");
				
				// Inform the user.
				makeNotice("All cargo containers have been deleted.", "warning", "Maintenance Result", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;

			// Truncate failed logins.
			case ("fla") :
				//Get down to business, clean the DB!
				$DB->query("TRUNCATE failed_logins;");
				
				// Inform the user.
				makeNotice("All failed logins have been deleted.", "warning", "Maintenance Result", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;

			// Truncate succeeded logins.
			case ("logins") :
				// Get down to business, clean the DB!
				$DB->query("TRUNCATE auth;");
				
				// Inform the user.
				makeNotice("The authenticated sessions have been deleted. You need to relogin now.", "warning", "Maintenance Result", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;
				
			// Truncate API keys (err, wha!?).
			case ("apikeys") :
				// Get down to business, clean the DB!
				$DB->query("TRUNCATE api_keys;");
				
				// Inform the user.
				makeNotice("All API keys have been deleted.", "warning", "Maintenance Result", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;


			// Unhandle cases (!HACKING!)
			default :
			
				// Inform the user.
				makeNotice("Invalid maintenance action!", "error", "Huh?", "index.php?action=maintenance", "index.php?action=maintenance");
				
				// Done here (unreachable code!)
				break;

		}

	}

	
	/*
	 * Below is the main html table, with all the options.
	 */
	
	$table = new table(3, true);
	$table->addHeader(">> Maintenance");

	$table->addRow();
	$table->addCol("Number of cached images:");
	$imgCount = $DB->getCol("SELECT COUNT(id) AS meh FROM images");
	$table->addCol(number_format($imgCount[0], 0));
	if ($MySelf->isAdmin()) {
		$table->addCol("[<a href=\"index.php?action=maintenance&wipe=images\">wipe database</a>]");
	} else {
		$table->addCol("insufficient rights");
	}

	$table->addRow();
	$table->addCol("Number of authenticated logins:");
	$authSess = $DB->getCol("SELECT COUNT(authkey) AS meh FROM auth");
	$table->addCol(number_format($authSess[0], 0));
	if ($MySelf->isAdmin()) {
		$table->addCol("[<a href=\"index.php?action=maintenance&wipe=logins\">wipe database</a>]");
	} else {
		$table->addCol("insufficient rights");
	}

	$table->addRow();
	$table->addCol("Number of failed login attempts:");
	$failLog = $DB->getCol("SELECT COUNT(incident) AS meh FROM failed_logins");
	$table->addCol(number_format($failLog[0], 0));
	if ($MySelf->isAdmin()) {
		$table->addCol("[<a href=\"index.php?action=maintenance&wipe=fla\">wipe database</a>]");
	} else {
		$table->addCol("insufficient rights");
	}

	$table->addRow();
	$table->addCol("Number of cans in database:");
	$canCount = $DB->getCol("SELECT COUNT(id) AS meh FROM cans");
	$table->addCol(number_format($canCount[0], 0));
	if ($MySelf->isAdmin()) {
		$table->addCol("[<a href=\"index.php?action=maintenance&wipe=cans\">wipe database</a>]");
	} else {
		$table->addCol("insufficient rights");
	}
	
	$table->addRow();
	$table->addCol("Number of API Keys in database:");
	$apiKeys = $DB->getCol("SELECT COUNT(userid) AS meh FROM api_keys");
	$table->addCol(number_format($apiKeys[0], 0));
	if ($MySelf->isAdmin()) {
		$table->addCol("[<a href=\"index.php?action=maintenance&wipe=apikeys\">wipe database</a>]");
	} else {
		$table->addCol("insufficient rights");
	}

	// Assemble and return.
	$table->addHeader("Read the Maintenance help!");
	$page = "<h2>Site Maintenance</h2>" . $table->flush() . file_get_contents('./include/html/maintenance-help.txt');
	return ($page);

}
?>
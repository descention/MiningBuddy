<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/makeDB.php,v 1.8 2008/01/02 20:01:32 mining Exp $
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
* function MakeDB
* This creates the database from the dsn and returns the dbObject.
*/
function makeDB() {
	/* Connects to the database.
	* Configuration is taken from the config.php file.
	* Returns a database object uppon success,
	* die()'s on any kind of error.
	*/

	// We need the DSN from the config file.
	global $DSN;

	// Create the database object.
	$database = DB :: connect("$DSN");

	// Did we encounter an error?
	if (DB :: isError($database)) {
		$dberror = $database->getMessage();
		// Print out the basic idea of an error, along with the raw PEAR::DB Error.
		print ("There is a problem communicating with your database server: $dberror<br>");

		// Catch the most common mistake: Invalid credentials.
		if ($dberror == "DB Error: connect failed") {
			print ("It seems you did not supply the correct mysql access information in the config.php.");
		}
		// Fall over, dead.
		die();
	}

	// Set database to associative fetching mode.
	$database->setFetchMode(DB_FETCHMODE_ASSOC);

	// and return the database object.
	return $database;
}
?>
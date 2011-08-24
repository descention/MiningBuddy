<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/listUsers.php,v 1.14 2008/05/03 11:45:45 mining Exp $
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
 * This function lists all current user in the database for admins.
 */

function listUsers() {

	// Some major vars importing...
	global $DB;
	global $MySelf;

	// Are we allowed to be here?
	if (!$MySelf->canSeeUsers()) {
		makeNotice("You are not allowed to delete runs!", "error", "forbidden");
	}

	// Easy-for-the-coder table generator.
	$fields = array (
		"ID",
		"Username",
		"Last Login",
		"can Login",
		"Confirmed",
		"Email OK",
		"API verified"
	);

	// Show only new users.
	if ($_GET["newusers"] == "true") {
		$users = $DB->Query("SELECT * FROM users WHERE confirmed='0' AND emailvalid='1' AND deleted ='0' ORDER BY username ASC;");
		$showOnlyNew = true;
		$newOnlyUrlAddition = "&newusers=true";
	} else {

		// Sorting switch, called by browser.
		switch ("$_GET[sort]") {

			case ("0") :
				$SORT = "id";
				break;

			case ("1") :
				$SORT = "username";
				break;

			case ("2") :
				$SORT = "lastlogin";
				break;

			case ("3") :
				$SORT = "canLogin";
				break;

			case ("4") :
				$SORT = "confirmed";
				break;

			case ("5") :
				$SORT = "emailvalid";
				break;

			default :
				$SORT = "username";
				break;

		}

		// Normal or reverse sorting.
		if ($_GET["r"] == "true") {
			// Reverse sorting!
			$SORTORDER = "DESC";
		} else {
			// Normal Order
			$SORTORDER = "ASC";
		}

		// Limit folks.
		if (isset ($_GET[sort])) {
			$sort = "&sort=" . $_GET[sort];
		}
		$folkCount = $DB->getCol("SELECT count(id) FROM users");
		if ($folkCount[0] > 25) {
			if (isset ($_GET[l])) {
				$l = sanitize($_GET[l]);
			} else {
				$l = "a";
			}

			// make the clickable thingy.
			for ($p = "a"; $p != "aa"; $p++) {
				$count = $DB->getCol("SELECT id FROM users WHERE username LIKE '" . $p . "%' AND confirmed='1'");
				if ($count[0] > 0) {
					$clickThingy .= " [<a href=\"index.php?action=editusers&l=" . $p . $sort . $newOnlyUrlAddition . "\">" . $p . "</a>]";
				} else {
					$clickThingy .= " [" . $p . "]";
				}
			}
		}

		if ($_GET[sort] == "6") {
			$users = $DB->query("select * from users, api_keys where users.confirmed='1' AND users.deleted='0' AND api_keys.userid = users.id AND username LIKE '" . $l . "%' order by users.username $SORTORDER");
		} else {
			// Query the database according to rights.
			$users = $DB->query("select * from users  where deleted='0' AND confirmed='1' AND username LIKE '" . $l . "%' order by $SORT $SORTORDER");
		}
	}

	// Build the tables.
	$table = new table(7, true);
	$table->addHeader(">> Manage Users");
	$mode = array (
		"bold" => true,
		"align" => "left"
	);
	$table->addRow("#060644");
	$table->addCol($clickThingy, array (
		"colspan" => "7",
		"align" => "center"
	));

	$table->addRow("#060622");
	$i = "0";
	foreach ($fields as $field) {
		if (($_GET["sort"] == "$i") && ($_GET[r] != "true")) {
			// Offer reverse sorting.
			$table->addCol("<a href=\"index.php?action=editusers&sort=$i&r=true" . $newOnlyUrlAdditio . "\">$field</a>", $mode);
		} else {
			// Offer real sorting.
			$table->addCol("<a href=\"index.php?action=editusers&sort=$i" . $newOnlyUrlAddition . "\">$field</a>", $mode);
		}
		$i++;
	}

	// create the database.
	if ($users->numRows() == 0 && $showOnlyNew == true) {
		$table->addRow();
		$table->addCol("There are no new users waiting.", array (
			"align" => "center",
			"colspan" => "7"
		));
	} else {
		while ($row = $users->fetchRow()) {

			$table->addRow();
			$table->addCol("<a href=\"index.php?action=edituser&id=$row[id]\">" . str_pad($row[id], 5, "0", STR_PAD_LEFT));
			$table->addCol(ucfirst($row[username]));

			// Handle folks that never logged in.
			if ("$row[lastlogin]" < 10) {
				$table->addCol("<i>never</i>");
			} else {
				$table->addCol(date("d.m.y H:i:s", $row[lastlogin]));
			}

			// Color the background accordingly.
			if ("$row[canLogin]" == "1") {
				$fcolor = "#00ff00";
			} else {
				$fcolor = "#ff0000";
			}
			$table->addCol("<a href=\"index.php?action=toggleLogin&id=" . $row[id] . "\"><font color=\"" . $fcolor . "\">" . yesno($row[canLogin]) . "</font></a>");

			// Color the background accordingly.
			if ("$row[confirmed]" == "1") {
				$fcolor = "#00ff00";
			} else {
				$fcolor = "#ff0000";
			}

			if ($newOnlyUrlAddition) {
				$table->addCol("<font color=\"" . $fcolor . "\"><a href=\"index.php?action=quickconfirm&id=" . $row[id] . "\">" . yesno($row[confirmed]) . "</a></font>");
			} else {
				$table->addCol("<font color=\"" . $fcolor . "\">" . yesno($row[confirmed]) . "</font>");
			}

			// Color the background accordingly.
			if ("$row[emailvalid]" == "1") {
				$fcolor = "#00ff00";
			} else {
				$fcolor = "#ff0000";
			}
			$table->addCol("<font color=\"" . $fcolor . "\">" . yesno($row[emailvalid]) . "</font>");

			$api = new api($row[id], true);
			if ($api->valid()) {
				// Api key submited and valid.
				$apiText = "<font color=\"#00ff00\">API valid</font>";
			} else {
				// Check wheter key is submited or "just" not valid.
				if ($api->getApiID() > 0) {
					// Api key submited but not valid.
					$apiText = "<font color=\"#FF8000\">API invalid</font>";
				} else {
					// No api key submited.
					$apiText = "<font color=\"#999999\">No api key</font>";
				}
			}
			$table->addCol($apiText, array (
				"bgcolor" => "$tdcolor"
			));
		}
	}

	if ($showOnlyNew) {
		$add = "<a href=\"index.php?action=editusers\">Show active users</a>";
	} else {
		$add = "<a href=\"index.php?action=editusers&newusers=true\">Show pending requests</a>";
	}
	$table->addHeader("Click on an ID to edit/view an user. " . $add);

	return ("<h2>User Management</h2>" . $table->flush());

}
?>
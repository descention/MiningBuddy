<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/admin/listUser.php,v 1.2 2008/05/01 15:37:24 mining Exp $
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

// Prints out a detailed list for the selected user.

function listUser() {
	global $DB;
	global $MySelf;
	global $IGB;
	global $TIMEMARK;
	global $IGB_VISUAL;

	// Are we allowed to peek into peoples files?
	if (!$MySelf->canSeeUsers()) {
		makeNotice("You are not allowed to do this!", "error", "forbidden");
	}

	// Is the supplied ID truly numeric?
	if (!is_numeric($_GET['id'])) {
		makeNotice("Why would you do such a thing? Are you evil at heart? Lets assume its a \"mistake\" for now..", "warning", "*cough*", "index.php?action=editusers", "I'm sorry...");
	} else {
		$id = (int) $_GET['id'];
	}

	// Query the database.
	$users = $DB->query("select * from users where id='$id' AND deleted='0' limit 1");

	// Build the tables.
	while ($row = $users->fetchRow()) {

		$table = new table(2, true);
		$table->addHeader(">> Managing user " . ucfirst($row['username']));
		$username = ucfirst($row['username']);

		$table->addRow();
		$table->addCol("ID:");
		$table->addCol(str_pad($row['id'], 5, "0", STR_RIGHT_PAD));

		$table->addRow();
		$table->addCol("Username:");

		// Allow ubah-admins to change usernames. WAH, can of worms!
		if ($MySelf->isAdmin() && $MySelf->canManageUser()) {
			$uname_temp = strtolower($row['username']);
			$field_temp = "<input type=\"text\" name=\"username\" value=\"" . $uname_temp . "\">";
			$confi_temp = "<input type=\"checkbox\" name=\"username_check\" value=\"true\">";
			$table->addCol($field_temp . " (changing username tick here also: " . $confi_temp . ")");
		} else {
			$table->addCol(ucfirst($row['username']));
		}

		$table->addRow();
		$table->addCol("eMail:");
		$table->addCol("<input type=\"text\" size=\"40\" name=\"email\" value=\"" . ($row['email'] == "" ? 'no email supplied' : $row['email']) . "\">");

		$table->addRow();
		$table->addCol("Password:");
		$table->addCol("<input type=\"password\" size=\"40\" name=\"password\">");

		// Ranks System
		$RanksDS = $DB->query("SELECT * FROM ranks ORDER BY rankOrder ASC");
		$rankCount = $RanksDS->numRows();

		if ($rankCount >= 1) {
			// We have at least 1 rank.
			while ($rank = $RanksDS->fetchRow()) {
				if ($rank['rankid'] == $row['rank']) {
					// The current rank is selected.
					$rank_pdm .= "<option SELECTED value=\"".$rank['rankid']."\">".$rank['name']."</option>";
				} else {
					// The others of course, are not.
					$rank_pdm .= "<option value=\"".$rank['rankid']."\">".$rank['name']."</option>";
				}
			}
			$rankColumn = "<select name=\"rank\">" . $rank_pdm . "</select>";
		} else {
			// No rank has been set yet.
			$rankColumn = "There are no ranks. Go create some!";
		}

		$table->addRow();
		$table->addCol("Rank:");
		$table->addCol($rankColumn);

		$table->addRow();
		$table->addCol("Last login:");

		// Handle folks that never logged in.
		if ($row['lastlogin'] < 10) {
			$table->addCol("never");
		} else {
			$table->addCol(date("d.m.y H:i:s", $row['lastlogin']));
		}

		$table->addRow();
		$table->addCol("Credits:");
		$table->addCol(number_format(getCredits($row['id']), 2) . " ISK");

		// Is the account confirmed?
		if ($row['confirmed'] == 0) {

			$table->addRow();
			$table->addCol("Account confirmed:");
			$table->addCol("This account has <b>not</b> been confirmed yet.");

			$table->addRow();
			$table->addCol("Confirm account:");
			$table->addCol("<input type=\"checkbox\" name=\"confirm\" value=\"true\"> Tick box to confirm account. <br><br>This is a one-way action only. Once an account" . " has been confirmed you can not unconfirm it. Tho you can block or delete it." . " Be careful not to confirm an account by accident - you could allow a non-authorized third party to access your MiningBuddy!");

			$table->addRow();
			$table->addCol("Account confirmed:");

			// Give a red light if user has not even verified himself.
			if ($row['emailvalid'] == "0") {
				$table->addCol("<b>WARNING!</b><br> The User has not yet verified this email yet! If you choose to enable" . " this account at this time, be very sure that you know the person requesting the account!", array (
					"bgcolor" => "#662222"
				));
			} else {
				$table->addCol("<br><br><b>The user validated the email address.</b><br>");
			}

		} else {
			$table->addRow();
			$table->addCol("This account has been confirmed.");

			if ($row['emailvalid'] == "0") {
				$table->addCol("<font color=\"#ff0000\">WARNING!</b></font><br> The User has not verified this email but the account has been confirmed!");

				// Add a "confirm email" checkbox.
				$table->addRow();
				$table->addCol("Mark users email as valid:");
				$table->addCol("<input type=\"checkbox\" name=\"SetEmailValid\" value=\"true\">");

			} else {
				$table->addCol("The user validated the supplied email address.");
			}

		}

		/*
		 * API Goodness
		 */
		$api = new api($row['id'], true);
		$apit = new table(2, true);
		$apit->addHeader(">> Api information for " . ucfirst($row['username']));
		$apit->addRow();

		$apit->addCol("API Key in database:");
		if ($api->getApiID() && $api->getApiKey()) {
			$apit->addCol(yesno(1, true));
			$apit->addRow();
			$apit->addCol("API valid:");
			$apit->addCol(yesno($api->valid(), true));
			if ($api->valid()) {
				$apit->addRow();
				$apit->addCol("Character ID:");
				$apit->addCol($api->getCharacterID());
				$apit->addRow();
				$apit->addCol("Validated on:");
				$apit->addCol(date("d.m.Y H:i:s", $api->validatedOn()));
			}
			$apit->addRow();
			$apit->addCol("Remove API key from database:");
			$apit->addCol("[<a href=\"index.php?action=delapi&id=$id\">delete api key</a>]");
		} else {
			$apit->addCol(yesno(0));
		}

		// Permissions matrix
		$perms = array (
			"canLogin" => "log in",
			"canJoinRun" => "join mining Ops",
			"canCreateRun" => "create new mining Ops",
			"canCloseRun" => "close mining Ops",
			"canDeleteRun" => "delete mining Ops",
			"canAddHaul" => "haul from/to mining Ops",
			"canSeeEvents" => "view scheduled events",
			"canDeleteEvents" => "can delete events",
			"canEditEvents" => "add and delete scheduled events",
			"canChangePwd" => "change his own password",
			"canChangeEmail" => "change his own email",
			"canChangeOre" => "manage ore prices and enable/disable them.",
			"canAddUser" => "add new accounts",
			"canSeeUsers" => "see other accounts",
			"canDeleteUser" => "delete other accounts.",
			"canEditRank" => "edit other peoples ranks.",
			"canManageUser" => "grant and take permissions.",
			"isOfficial" => "create official mining runs (with payout).",
			"isAdmin" => "edit site settings.",
			"isLottoOfficial" => "administrate the lottery",
			"canPlayLotto" => "play Lotto!",
			"isAccountant" => "edit other users credits.",
			"optIn" => "User has opt-in to eMails."
		);

		// Create a seperate permissions table.
		$perm_table = new table(2, true);
		$perm_table->addHeader(">> " . ucfirst($row['username']) . " has permission to... ");

		$perm_keys = array_keys($perms);
		$LoR = 1;

		foreach ($perm_keys as $key) {

			if ($LoR) {
				$perm_table->addRow();
			}

			if ($row[$key]) {
				$perm_table->addCol("<input type=\"checkbox\" name=\"$key\" checked> " . $perms[$key]);
			} else {
				$perm_table->addCol("<input type=\"checkbox\" name=\"$key\"> " . $perms[$key]);
			}
			$LoR = 1 - $LoR;
		}

		if (!$LoR) {
			$perm_table->addCol();
		}

		// Delete User
		$perm_table->addRow();
		$perm_table->addCol("<hr>", array (
			"colspan" => 2
		));
		$perm_table->addRow();
		$perm_table->addCol("Delete user:");
		$perm_table->addCol("<input type=\"checkbox\" name=\"delete\" value=\"true\"> Tick box to delete the user permanently.");
		$perm_table->addRow();
		$perm_table->addCol("<hr>", array (
			"colspan" => 2
		));

		// Commit changes button.
		$perm_table->addHeaderCentered("<input type=\"submit\" name=\"send\" value=\"Commit changes\">", array (
			"colspan" => 2,
			"align" => "center"
		));

	}

	$form .= "<form action=\"index.php\" method=\"POST\">";
	$form .= "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['id'] . "\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$form .= "<input type=\"hidden\" name=\"action\" value=\"edituser\">";

	// Show all logins.
	$logins = getLogins($id);

	// Show failed logins.
	$failed_logins = showFailedLogins("15", idToUsername($id));

	/*
	 * Transactions.
	 */
	if ($MySelf->isAccountant()) {

		$acc = new table(2, true);
		$acc->addHeader(">> Create transaction to user " . ucfirst(idToUsername($id)));

		$acc->addRow();
		$acc->addCol("Credit to:");
		$acc->addCol($username);

		$acc->addRow();
		$acc->addCol("Authorization by:");
		$acc->addCol(ucfirst($MySelf->getUsername()));

		$acc->addRow();
		$acc->addCol("Time of Transaction:");
		$acc->addCol(date("r", $TIMEMARK));

		$acc->addRow();
		$acc->addCol("Withdrawal or deposit:");
		$pdm = "<select name=\"wod\">";
		$pdm .= "<option value=\"0\">Deposit (give money)</option>";
		$pdm .= "<option SELECTED value=\"1\">Withdrawal (take money)</option>";
		$pdm .= "</select>";
		$acc->addCol($pdm);

		$acc->addRow();
		$acc->addCol("Amount:");
		$acc->addCol("<input size=\"8\" type=\"text\" name=\"amount\"> ISK");

		$acc->addRow();
		$acc->addCol("Reason:");
		$pdm = "<select name=\"reason1\">";
		$pdm .= "<option>requested payout</option>";
		$pdm .= "<option SELECTED>normal payout</option>";
		$pdm .= "<option>payout of loan</option>";
		$pdm .= "<option>manual deposit</option>";
		$pdm .= "<option>cash recived</option>";
		$pdm .= "</select>";
		$acc->addCol($pdm . " -or- <input type=\"text\" name=\"reason2\">");

		$acc->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Authorize transaction\">");

		$transBox = "<form action=\"index.php\" method=\"POST\">";
		$transBox .= $acc->flush();
		$transBox .= "<input type=\"hidden\" name=\"id\" value=\"" . $_GET['id'] . "\">";
		$transBox .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
		$transBox .= "<input type=\"hidden\" name=\"action\" value=\"transaction\">";
		$transBox .= "</form>";
	}

	$page = "<h2>Managing user details</h2>" . $form . $table->flush() . "<br>" . $apit->flush() . "<br>" . $perm_table->flush() . "</form>" . $transBox;

	$transactions = getTransactions($id);
	if ($transactions) {
		$page .= $transactions;
	}

	// Add login table if we have more than 0 logins.
	if ($logins) {
		if ($transactions) {
			$page .= "<br>";
		}
		$page .= $logins;
	}

	$page .= $failed_logins;

	// Return the page.
	return ($page);
}
?>

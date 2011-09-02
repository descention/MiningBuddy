<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makePreferences.php,v 1.26 2008/01/06 19:41:51 mining Exp $
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

function makePreferences() {
	// I kid you not. All needed.
	global $PREFS;
	global $VERSION;
	global $SITENAME;
	global $TIMEMARK;
	global $DB;
	global $MySelf;

	/*
	 * Cantimer Settings
	 */

	$cantimer_table = new table(2, true);
	$cantimer_table->addHeader(">> Preferences for Cantimer");

	// Can see my own cans.
	$cantimer_table->addRow();
	if ($PREFS->getPref("CanMyCans")) {
		$cantimer_table->addCol("<input type=\"checkbox\" CHECKED name=\"CanMyCans\" value=\"true\">");
	} else {
		$cantimer_table->addCol("<input type=\"checkbox\" name=\"CanMyCans\" value=\"true\">");
	}
	$cantimer_table->addCol("Tick box to see your own cans.");

	// Can see the add cans form.
	$cantimer_table->addRow();
	if ($PREFS->getPref("CanAddCans")) {
		$cantimer_table->addCol("<input type=\"checkbox\" CHECKED name=\"CanAddCans\" value=\"true\">");
	} else {
		$cantimer_table->addCol("<input type=\"checkbox\" name=\"CanAddCans\" value=\"true\">");
	}
	$cantimer_table->addCol("Tick the add can form.");

	// Can See cans beloning to same run.
	$cantimer_table->addRow();
	if ($PREFS->getPref("CanRunCans")) {
		$cantimer_table->addCol("<input type=\"checkbox\" CHECKED name=\"CanRunCans\" value=\"true\">");
	} else {
		$cantimer_table->addCol("<input type=\"checkbox\" name=\"CanRunCans\" value=\"true\">");
	}
	$cantimer_table->addCol("Tick to see cans beloning to your MiningOp.");

	// Can see all cans.
	$cantimer_table->addRow();
	if ($PREFS->getPref("CanAllCans")) {
		$cantimer_table->addCol("<input type=\"checkbox\" CHECKED name=\"CanAllCans\" value=\"true\">");
	} else {
		$cantimer_table->addCol("<input type=\"checkbox\" name=\"CanAllCans\" value=\"true\">");
	}
	$cantimer_table->addCol("Tick if you want to see all cans.");

	$cantimer_table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update Can Timer settings\">");

	/*
	 * Opt In/Out of emails Setting
	 */

	$opt_table = new table(2, true);
	$opt_table->addHeader(">> Your eMail settings");

	$opt_table->addRow();
	if ($MySelf->optInState()) {
		$opt_table->addCol("<input type=\"checkbox\" CHECKED name=\"optIn\" value=\"true\">");
	} else {
		$opt_table->addCol("<input type=\"checkbox\" name=\"optIn\" value=\"true\">");
	}
	$opt_table->addCol("Tick this to recive eMails from MiningBuddy. You will get eMails that will inform you about new events entered into the system, Mining Run reciepts and the occasional CEO email.");
	$opt_table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update your eMail preferences\">");

	/*
	 * Show/hide inofficial runs
	 */

	$sir_table = new table(2, true);
	$sir_table->addHeader(">> Show/Hide inofficial runs");
	$sir_table->addRow();

	if ($PREFS->getPref("sirstate")) {
		$sir_table->addCol("<input type=\"checkbox\" CHECKED name=\"sir\" value=\"true\">");
	} else {
		$sir_table->addCol("<input type=\"checkbox\" name=\"sir\" value=\"true\">");
	}
	$sir_table->addCol("Tick the box to show non-official mining operations. Your own inofficial mining runs are still shown, however.");
	$sir_table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update your settings\">");

	/*
	 * Update eMail address.
	 */

	if ($MySelf->canChangeEmail()) {

		$email_table = new table(2, true);
		$email_table->addHeader(">> Update your eMail address");
		$email_table->addRow("#060622");
		$email_table->addCol("Your email is needed to send password hints and event news.", array (
			"colspan" => 2
		));

		// Query the oracle.
		$email_table->addRow();
		$email = $DB->getCol("select email from users where username = '" . sanitize($MySelf->getUsername()) . "' AND deleted='0' limit 1");
		$email_table->addCol("Current eMail:");
		$email_table->addCol("<input type=\"text\" readonly value=\"" . $email[0] . "\">");
		$email_table->addRow();
		$email_table->addCol("New eMail:");
		$email_table->addCol("<input type=\"text\" name=\"email\" maxlength=\"100\">");
		$email_table->addHeaderCentered("<input type=\"submit\" name=\"change\" value=\"Update your eMail\">");

	}

	/*
	 * Change password.
	 */
	if ($MySelf->canChangePwd()) {
		$password_table = new table(2, true);
		$password_table->addHeader(">> Change your password");
		$password_table->addRow("#060622");
		$password_table->addCol("Its always a good idea to change your password frequently. Your password is " .
		"stored in an encrypted form; no one will ever be able to read it.", array (
			"colspan" => "2"
		));
		$password_table->addRow();
		$password_table->addCol("Changing password for:");
		$password_table->addCol(ucfirst($MySelf->getUsername()));
		$password_table->addRow();
		$password_table->addCol("Your current password:");
		$password_table->addCol("<input type=\"password\" name=\"password\" maxlength=\"20\">");
		$password_table->addRow();
		$password_table->addCol("Set a new password:");
		$password_table->addCol("<input type=\"password\" name=\"password1\" maxlength=\"20\">");
		$password_table->addRow();
		$password_table->addCol("Verify your new password:");
		$password_table->addCol("<input type=\"password\" name=\"password2\" maxlength=\"20\">");
		$password_table->addHeaderCentered("<input type=\"submit\" name=\"change\" value=\"Update your password\">");
	}

	// Assemble the html.
	$page = "<h2>Your Preferences</h2>";
	$page .= "<form action=\"index.php\" method=\"POST\">";
	$page .= $cantimer_table->flush();
	$page .= "<input type=\"hidden\" name=\"action\" value=\"changecanpage\">";
	$page .= "<input type=\"hidden\" name=\"check\" value=\"check\"></form>";

	$page .= "<form action=\"index.php\" method=\"POST\">";
	$page .= $opt_table->flush();
	$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$page .= "<input type=\"hidden\" name=\"action\" value=\"optIn\"></form>";

	$page .= "<form action=\"index.php\" method=\"POST\">";
	$page .= $sir_table->flush();
	$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$page .= "<input type=\"hidden\" name=\"action\" value=\"sirchange\"></form>";

	if ($MySelf->canChangeEmail()) {
		$page .= "<form action=\"index.php\" method=\"post\">";
		$page .= $email_table->flush();
		$page .= "<input type=\"hidden\" name=\"action\" value=\"changeemail\">";
		$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
		$page .= "</form>";
	}

	if ($MySelf->canChangePwd()) {
		$page .= "<form action=\"index.php\" method=\"post\">";
		$page .= $password_table->flush();
		$page .= "<input type=\"hidden\" name=\"action\" value=\"changepw\">";
		$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
		$page .= "<input type=\"hidden\" name=\"username\" value=\"%%USERNAME%%\">";
		$page .= "</form>";
	}

	// Api Keys

	// Load possible current keys.
	$api = new api($MySelf->getID());
	$api_key = $api->getApiKey();
	$api_id = $api->getApiID();

	if (!$api->valid()) {
		$api->authorizeApi();
	}

	// Do the api table.
	$api_table = new table(2, true);
	$api_table->addHeader(">> Api key management");
	$api_table->addRow();
	$api_table->addCol("Here you can supply your limited-access API-Key. Its used for quick-login for now.", array (
		"colspan" => 2
	));

	if ($api_id && $api->valid()) {
		$s1 = "<input type =\"hidden\" name=\"apiID\" value=\"$api_id\">$api_id";
	} else {
		$s1 = "<input type=\"text\" name=\"apiID\" value=\"$api_id\">";
		$doApiLink = true;
	}

	if ($api_key && $api->valid()) {
		$s2 = "<input type =\"hidden\" name=\"apiKey\" value=\"$api_key\">$api_key";
	} else {
		$s2 = "<input type=\"text\" size=\"80\" name=\"apiKey\" value=\"$api_key\">";
		$doApiLink = true;
	}

	$api_table->addRow();
	$api_table->addCol("API ID:");
	$api_table->addCol($s1);
	$api_table->addRow();
	$api_table->addCol("Verification Code:");
	$api_table->addCol($s2);

	// Add the API link to eve online.
	global $IGB;
	global $IGB_VISUAL;
	if ($doApiLink && (!$IGB || ($IGB && $IGB_VISUAL))) {
		$api_table->addRow();
		$api_table->addCol("Get your API key here:");
		$api_table->addCol("<a href=\"http://support.eveonline.com/api/default.asp\">http://support.eveonline.com/api/default.asp</a>");
	}

	if ($api_key) {
		$api_table->addRow();
		$api_table->addCol("API Key validated:");
		if (!$api->valid()) {
			$hint = " (If your key is not validated, hit update API key button.)";
		}
		$api_table->addCol(yesno($api->valid(), yes) . $hint);
		if ($api->valid()) {
			$api_table->addRow();
			$api_table->addCol("Validated on:");
			$api_table->addCol(date("d.m.Y H:i:s", $api->validatedOn()));
			$api_table->addRow();
			$api_table->addCol("Next verification at:");
			$api_table->addCol(date("d.m.Y H:i:s", $api->nextValidation()));
			$api_table->addRow();
			$api_table->addCol("API keys are valid for:");
			$days = getConfig("api_keys_valid");
			if ($days <= 1) {
				$days = "1 day.";
			} else {
				$days = $days . " days.";
			}

			$api_table->addCol($days);
			$api_table->addRow();
			$api_table->addCol("Character ID:");
			$api_table->addCol($api->getCharacterID());
		}
		$api_table->addRow();
		$api_table->addCol("Remove Key:");
		$api_table->addCol("<input type=\"checkbox\" name=\"deleteKey\" value=\"true\"> Tick box to remove the API key from the Database.");
	}
	$api_table->addHeaderCentered("<input type=\"submit\" name=\"update_api\" value=\"Update API Key\">");
	$api_form .= "<form action=\"index.php\" method=\"POST\">";
	$api_form .= $api_table->flush();
	$api_form .= "<input type=\"hidden\" name=\"action\" value=\"update_api\">";
	$api_form .= "<input type=\"hidden\" name=\"check\" value=\"check\"></form>";

	$page .= $api_form;
	// We are done here.

	return ($page);

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeRequestAccountPage.php,v 1.15 2008/10/22 12:15:15 mining Exp $
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

// prints a neat login page.
function makeRequestAccountPage($failedFastLogin = false) {

	// We need global Variables.
	global $VERSION;
	global $SITENAME;
	global $IGB;
	global $IGB_VISUAL;

	if ($IGB && $IGB_VISUAL) {
		$table = new table(2, true);
	} else {
		$table = new table(2, true, "width=\"500\"", "align=\"center\"");
	}
	
	if (isset($_GET['admin']) && $_GET['admin'] == true){
		$table->addHeader(">> Create initial Superadmin account");	
	} else {
		$table->addHeader(">> Request an account");
	}
	
	// Trust, INC.
	if ($failedFastLogin) {
		// This happens when someone allowed fast logins(!) and the user does not exist.
		global $EVE_Charname;		
		$table->addRow("#660000");
		$table->addCol("Fast login failed; Username \"".ucfirst($EVE_Charname)."\" does not exist.", array ("colspan" => 2, "align"=>"center"));
	}
	
	$table->addRow("#060622");
	
	if (isset($_GET['admin']) && $_GET['admin'] == true){
		$table->addCol("Fill out the form below to create the initial superadmin account. " .
				"This account will have all priviledges - so keep the login credentials safe! " .
				"Your password will be randomly generated and revealed to you just once, " .
				"so write it down or copy it elsewhere. You will have the option to " .
				"change your password on your first login.",
			array ("colspan" => 2));
	} else {
		$table->addCol("Fill out the form below to apply for a new account. After you requested " .
			"an account you will receive an email with an activation link. Finally, your " .
			"CEO has to approve of your account, after which you will receive your initial password.",
			array ("colspan" => 2));
	}

	$table->addRow();
	$table->addCol("Character Name:", array());
	
	// Trust, INC.
	global $EVE_Charname;
	if ($EVE_Charname) {
		$table->addCol("<input type=\"text\" name=\"username\" value=\"$EVE_Charname\" maxlength=\"30\">", array());
	} else {
		$table->addCol("<input type=\"text\" name=\"username\" maxlength=\"30\">", array());
	}

	$table->addRow();
	$table->addCol("Your valid eMail:");
	$table->addCol("<input type=\"text\" name=\"email\" maxlength=\"70\">", array());

	if (!isset($_GET['admin']) || $_GET['admin'] == false){
		$table->addHeaderCentered("<input type=\"submit\" name=\"login\" value=\"request account\">");
		$table->addRow("#060622");
		$table->addCol("[<a href=\"index.php\">Cancel request</a>]", array (
			"colspan" => 2
		));
	} else {
		$table->addHeaderCentered("<input type=\"submit\" name=\"login\" value=\"Create Superadmin\">");
	}

	$page = "<br><br>";
	$page .= "<form action=\"index.php\" method=\"post\">";
	$page .= "<input type=\"hidden\" name=\"action\" value=\"requestaccount\">";
	$page .= $table->flush();
	$page .= "</form><br><br>";

	// Print it, and die (special case: login does not get beautified.)
	$html = new html;
	$html->addBody($page);
	die($html->flush());
}
?>

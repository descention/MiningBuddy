<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeLostPassForm.php,v 1.14 2008/01/02 20:01:32 mining Exp $
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

// Prints the password change form.
function makeLostPassForm() {

	// We need some global vars again.
	global $IGB;
	global $SITENAME;
	global $IGB_VISUAL;

	if ($IGB && $IGB_VISUAL) {
		$table = new table(2, true);
	} else {
		$table = new table(2, true, "width=\"500\"", "align=\"center\"");
	}
	$table->addHeader(">> Request a new password");
	$table->addRow("#060622");
	$table->addCol("Fill out the form below to have a new password generated and sent to you registered eMail address.", array (
		"colspan" => 2
	));

	$table->addRow();
	$table->addCol("Character Name:");
	
	// Trust, INC.
	global $EVE_Charname;
	if ($EVE_Charname) {
		$table->addCol("<input type=\"text\" name=\"username\" value=\"$EVE_Charname\" maxlength=\"30\">");
	} else {
		$table->addCol("<input type=\"text\" name=\"username\" maxlength=\"30\">");		
	}

	$table->addRow();
	$table->addCol("Your valid eMail:");
	$table->addCol("<input type=\"text\" name=\"email\" maxlength=\"70\">");

	$table->addHeaderCentered("<input type=\"submit\" name=\"change\" value=\"Get Password\">");

	$table->addRow("#060622");
	$table->addCol("[<a href=\"index.php\">Cancel request</a>]", array (
		"colspan" => 2
	));

//	$page = "<h2>Lost password</h2>";
	$page = "<br><br>";
	$page .= "<form action=\"index.php\" method=\"post\">";
	$page .= "<input type=\"hidden\" name=\"action\" value=\"lostpass\">";
	$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$page .= $table->flush();
	$page .= "</form><br><br>";

	// Print it, and die (special case: login does not get beautified.)
	$html = new html;
	$html->addBody($page);
	die($html->flush());

}
?>
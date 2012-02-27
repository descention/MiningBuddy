<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeAddUserForm.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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

function makeAddUserForm() {

	// Are we allowed to?
	global $MySelf;
	if (!$MySelf->canAddUser()) {
		makeNotice("You are not authorized to do that!", "error", "Forbidden");
	}

	// Suggest a user password.
	$suggestedPassword = crypt(base64_encode(rand(11111, 99999)), "8ewf7tg2k,leduj");

	$table = new table(2, true);
	$table->addHeader(">> Add a new user");
	$table->addRow("#060622");
	$table->addCol("You can manually add a new user with this form. But use this only " .
	"as a last resort, for example, if your server can not send eMails. " .
	"Always let the user request an account. This form was supposed to be " .
	"removed, but complains from the users kept it alive.", array (
		"colspan" => 2
	));

	$table->addRow();
	$table->addCol("Username:");
	$table->addCol("<input type=\"text\" name=\"username\" maxlength=\"20\">");

	$table->addRow();
	$table->addCol("eMail:");
	$table->addCol("<input type=\"text\" name=\"email\">");

	$table->addRow();
	$table->addCol("Password:");
	$table->addCol("<input type=\"password\" name=\"pass1\" value=\"$suggestedPassword\"> (Suggested: $suggestedPassword)");

	$table->addRow();
	$table->addCol("Verify Password:");
	$table->addCol("<input type=\"password\" name=\"pass2\" value=\"$suggestedPassword\">");

	$table->addHeaderCentered("<input type=\"submit\" name=\"create\" value=\"Add user to database\">");

	$page = "<h2>Add a new User</h2>";
	$page .= "<form action=\"index.php\" method=\"post\">";
	$page .= $table->flush();
	$page .= "<input type=\"hidden\" name=\"action\" value=\"newuser\">";
	$page .= "<input type=\"hidden\" name=\"check\" value=\"check\">";
	$page .= "</form>";

	return ($page);
}
?>
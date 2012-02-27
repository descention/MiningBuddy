<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/confirm.php,v 1.10 2008/01/05 14:08:11 mining Exp $
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

function confirm($question = "Are you sure?") {

	// switch post or get.
	if (isset ($_POST[check])) {

		// The user confirmed the box. Dont loop. Accept it already ;)
		if ($_POST[confirmed] == true) {
			return (true);
		}

		$MODE = "POST";
		$FORM = $_POST;
		$keys = array_keys($_POST);

	} else {

		// The user confirmed the box. Dont loop. Accept it already ;)
		if ($_GET[confirmed] == true) {
			return (true);
		}

		$MODE = "GET";
		$FORM = $_GET;
		$keys = array_keys($_GET);

	}

	// Assemble hidden values for the confirm form.
	foreach ($keys as $key) {
		$html .= "<input type=\"hidden\" name=\"" . $key . "\" value=\"" . $FORM[$key] . "\">";
	}
	
	// Cancel button
	$cancel  = "<form action=\"index.php\" method=\"POST\">";
	$cancel .= "<input type=\"submit\" name=\"confirmed\" value=\"CANCEL\">";
	$cancel .= "</form>";

	// OK button
	$ok = "<form action=\"index.php\" method=\"$MODE\">";
	$ok .= $html;
	$ok .= "<input type=\"submit\" name=\"confirmed\" value=\"OK\">";
	$ok .= "</form>";

	$table = new table("2", true, "width=\"50%\"", "align=\"center\"");
	$table->addHeader("<img src=\"./images/warning.png\">");
	$table->addRow("#060622");
	
	$table->addCol(">> Confirmation needed", array("colspan"=>"2"));
	$table->addRow();
	$table->addCol("<br>". $question . "<br><br>", array("colspan"=>"2"));
	$table->addRow();
	$table->addCol($cancel, array("align"=>"left"));
	$table->addCol($ok, array("align"=>"right"));

	$htmlobj = new html;
	$htmlobj->addBody("<br><br><br><br>" . $table->flush() . "<br><br><br><br>");
	die($htmlobj->flush());

}
?>
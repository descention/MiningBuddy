<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/lotto/lotto_editLottery.php,v 1.8 2007/02/02 17:32:42 mining Exp $
 *
 * Copyright (c) 2005, 2006, 2007 Christian Reiss.
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

function lotto_editLottery() {

	// We need some globals
	global $MySelf;
	global $DB;
	
	$formDisable = "";
	
	if (lotto_getOpenDrawing()) {
		$formDisable = "disabled";	
	}
		
	// is Lotto enabled at all?
	if (!getConfig("lotto")) {
		makeNotice("Your CEO disabled the Lotto module, request denied.", "warning", "Lotto Module Offline");
	}

	// Deny access to non-lotto-officials.
	if (!$MySelf->isLottoOfficial()) {
		makeNotice("You are not allowed to do this!", "error", "Permission denied");
	}

	$table = new table(2, true);
	$table->addHeader(">> Open new drawing");

	$table->addRow();
	$table->addCol("Number of tickets in draw:");
	$table->addCol("<input type=\"text\" name=\"count\" " . $formDisable ." value=\"30\">");

	//	$newLotto = new table (2);
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" " . $formDisable ." value=\"open new drawing\">", array (
		"bold" => true,
		"colspan" => 2
	));

	$html = "<h2>Lotto Administration</h2>";
	$html .= "<form action=\"index.php\" method=\"POST\">";
	$html .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$html .= "<input type=\"hidden\" name=\"action\" value=\"createDrawing\">";
	$html .= $table->flush();
	$html .= "</form>";
	
	if (lotto_getOpenDrawing()) {
		$html .= "[<a href=\"index.php?action=drawLotto\">Draw Winner</a>]";
	}
	
	return ($html);
}
?>
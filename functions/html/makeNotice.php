<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeNotice.php,v 1.21 2008/01/02 20:37:23 mining Exp $
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
 * This handy little function will create a small dead-end html
 * page, used for notices, actions and the like.
 */

function makeNotice($body, $type = "notice", $title = "", $backlink = "index.php", $backlinkdesc = "[OK]") {

	global $IGB;
	global $IGB_VISUAL;
	global $MySelf;

	// Check for valid type
	switch ($type) {
		case ("notice") :
			$typeText = "Notice";
			$color = "#444455";
			$img = "ok.png";
			break;

		case ("warning") :
			$typeText = "Warning";
			$color = "#904000";
			$img = "warning.png";
			break;

		case ("error") :
			$typeText = "Error";
			$color = "#772222";
			$img = "error.png";
			break;

		default :
			// Yeah, we call ourselves!
			$BT = nl2br(print_r(debug_backtrace(), true));
			makeNotice("Internal Error: Wrong errortype defines in makeNotice.<br><br>" . $BT, "error");
			break;
	}

	// Use global variables.
	global $IGB;
	global $VERSION;
	global $TIMEMARK;
	global $TIDY_ENABLE;
	global $MySelf;

	// Do we have a title? 
	if (empty ($title)) {
		$title = "$VERSION - Notice";
	}

	// Do we have a body? 
	if (empty ($title)) {
		makeErrorPage("makeNotice called without body context.");
	}

	// Beautify the time.
	$STAMP = date("r", $TIMEMARK);

	// Assemble the raw html page.
	//$HTML = $page;
	$HTML = "";

	if ($IGB && $IGB_VISUAL) {
		$HTML .= file_get_contents('./include/ingame/igb-notice.txt');
	} else {
		$HTML .= file_get_contents('./include/html/notice.txt');
	}

	//$HTML .= $footer;

	// Replace placeholders with information.
	$HTML = str_replace("%%TITLE%%", "$title", $HTML);
	$HTML = str_replace("%%BODY%%", "$body", $HTML);
	$HTML = str_replace("%%WHAT%%", "$typeText", $HTML);
	$HTML = str_replace("%%TIME%%", "$STAMP", $HTML);
	$HTML = str_replace("%%COLOR%%", "$color", $HTML);
	$HTML = str_replace("%%IMG%%", "$img", $HTML);
	$HTML = str_replace("%%BACKLINK%%", "$backlink", $HTML);
	$HTML = str_replace("%%BACKLINKDESC%%", "$backlinkdesc", $HTML);
	if (is_object($MySelf) && $MySelf->isValid()) {
		$HTML = str_replace("%%USER%%", "Logged in as " . $MySelf->getUsername(), $HTML);
	} else {
		$HTML = str_replace("%%USER%%", "Not logged in.", $HTML);
	}

	// Spill it out
	$htmlobj = new html;
	$htmlobj->addBody($HTML);
	die($htmlobj->flush());

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/system/errorHandler.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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
 * This is the custom PHP error handler.
 */

function errorHandler($number, $string, $file, $line, $context) {

	if ($number != E_NOTICE && $number != E_WARNING) {
		global $VERSION;
		$error = "<table><tr><td>";
		$error .= "Number:</td><td>[$number]</tr></tr>";
		$error .= "<tr><td>String :</td><td> [$string]</tr></tr>";
		$error .= "<tr><td>File   :</td><td> [$file]</tr></tr>";
		$error .= "<tr><td>Line   :</td><td> [$line]</tr></tr>";
		//		$error .= "<tr><td>Context:</td><td>".nl2br(print_r($context))."</tr></tr></table>";

//		$html = file_get_contents('./include/html/errorHandler.txt');
		$html = file_get_contents('./include/html/errorHandler.php');
		$html = str_replace("%%ERROR%%", $error, $html);
		$html = str_replace("%%SITENAME%%", $VERSION, $html);

		print ($html);
		die();
	}

}
?>
<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/system/printImage.php,v 1.2 2008/01/06 19:48:39 mining Exp $
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

//$IS_BETA=false;
//$g = new graphic("standard");
//$g->setText("Profile");
//$g->setBGColor("2d2d37");
//die($g->render());

if (isset ($_GET[image])) {
	// Load the preferences.
	$prefs = unserialize(base64_decode($_SESSION["img_".$_GET[image]]));
	unset($_SESSION["img_".$_GET[image]]);

	// Create a new empty shell for the  image.
	$p = new graphic($prefs[type]);

	// Restore the old parameters.
	$p->setDirect(true);
	$p->setSecondPass(true);
	$p->setPrefixed($prefs[prefixed]);
	$p->setText($prefs[text]);
	$p->setBGColor($prefs[bgcolor]);
	$p->setTextColor($prefs[color]);

	// Send png header to client.
	header('Content-Type: image/png');

	// Send the image.
	die($p->render());
}
?>
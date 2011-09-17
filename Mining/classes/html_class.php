<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/html_class.php,v 1.39 2008/05/01 15:37:24 mining Exp $
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

class html {

	// Set all variables to private
	private $header;
	private $footer;
	private $body;
	private $useTidy;
	private $isIGB;

	// Constructor
	public function __construct() {
		// Construct a different html for ingame and the out-of-game browser.
		global $IGB;
		global $VERSION;
		global $MySelf;
		global $TIDY_ENABLE;
		global $width;
		global $URL;
		global $IGB_VISUAL;

		// Enable tidy, if we want to.
		$this->useTidy = $TIDY_ENABLE;

		// In case we are not logged in, or the object does not exist yet.
		if (!is_object($MySelf)) {
			$MySelf = new user(false, false);
		}

		if ($IGB && $IGB_VISUAL) {

			// Use IGB, set header and footer.
			$this->isIGB = true;
//			$this->header = file_get_contents('./include/ingame/igb-header.txt');
			$this->header = file_get_contents('./include/ingame/igb-header.php');
			
			if ($MySelf->isValid()) {
				$this->header .= makeMenu();
				$this->header = str_replace("%%RANK%%", $MySelf->getRankName(), $this->header);
				$this->header = str_replace("%%CREDITS%%", number_format(getCredits($MySelf->getID()), 2) . " ISK", $this->header);
				$this->header = str_replace("%%USERNAME%%", ucfirst($MySelf->getUsername()), $this->header);
			}

			$this->header = str_replace("%%SITENAME%%", getConfig("sitename"), $this->header);
//			$this->footer = file_get_contents('./include/ingame/igb-footer.txt');
			$this->footer = file_get_contents('./include/ingame/igb-footer.php');
			$this->footer = str_replace("%%VERSION%%", $VERSION, $this->footer);

		} else {

			// Use normal browser.
			$this->isIGB = false;
			if ($MySelf->isValid() == 1) {
//				$this->header = file_get_contents('./include/html/header.txt');
				$this->header = file_get_contents('./include/html/header.php');
			} else {
//				$this->header = file_get_contents('./include/html/header-notloggedin.txt');
				$this->header = file_get_contents('./include/html/header-notloggedin.php');
			}

//			$this->footer = file_get_contents('./include/html/footer.txt');
			$this->footer = file_get_contents('./include/html/footer.php');

			// Generate the images.
			$mainLogo = new graphic("title");
			$mainLogo->setText(getConfig("sitename"));
			$mainLogo->setBGColor("2D2D37");

			$loginLogo = new graphic("standard");
			$loginLogo->setText(ucfirst($MySelf->getUsername()));
			$loginLogo->setBGColor("2D2D37");
			$loginLogo->setPrefixed(false);

			$versionLogo = new graphic("long");
			$versionLogo->setText($VERSION);
			$versionLogo->setBGColor("2D2D37");
			$versionLogo->setPrefixed(false);

			$rankLogo = new graphic("standard");
			$rankLogo->setText($MySelf->getRankName());
			$rankLogo->setBGColor("2D2D37");
			$rankLogo->setPrefixed(false);

			$moneyLogo = new graphic("standard");
			$moneyLogo->setText(number_format(getCredits($MySelf->getID()), 2) . " ISK");
			$moneyLogo->setDirect(true);
			$moneyLogo->setBGColor("2D2D37");
			$moneyLogo->setPrefixed(false);
						
			// Replace variables in the header.
			$this->header = str_replace("%%SITENAME%%", getConfig("sitename") . " - " . $VERSION, $this->header);
			$this->header = makeMenu($this->header);
			$thisCharacterID = "";
			if ($MySelf->isValid()){
				$api = new api($MySelf->getID());
				$thisCharacterID = $api->getCharacterID();
			}
			if ($thisCharacterID == "") {
				$this->header = str_replace("%%PILOT64%%", $MySelf->isValid(), $this->header);
			} else {
				$this->header = str_replace("%%PILOT64%%", "<img width='64' height='64' align='left' src='https://image.eveonline.com/Character/". $api->getCharacterID() ."_64.jpg' />", $this->header);
			}
			$this->header = str_replace("%%LOGGEDIN%%", $loginLogo->render(), $this->header);
			$this->header = str_replace("%%RANK%%", $rankLogo->render(), $this->header);
			$this->header = str_replace("%%CREDITS%%", $moneyLogo->render(), $this->header);
			$this->header = str_replace("%%URL%%", $URL, $this->header);
			$this->footer = str_replace("%%IMG%%", $versionLogo->render(), $this->footer);
			$this->header = str_replace("%%LOGO%%", $mainLogo->render(), $this->header);
			//$this->header = str_replace("%%PILOTIMAGE%%", $getImage("small"), $this->header);
		}

		$this->header = str_replace("%%VERSION%%", $VERSION, $this->header);

		$this->header .= "<!--header ends here-->";
		$this->footer = "<!--footer starts here-->" . $this->footer;
	}

	// This will allow us to add (more) body.
	public function addBody($html) {

		// Replace some code for the IGB.
		if ($this->isIGB) {
			$html = str_replace("align=\"center\"", "", $html);
			$html = str_replace("%%WIDTH%%", "width=\"99%\"", $html);
		} else {
			$html = str_replace("%%WIDTH%%", "width=\"100%\"", $html);
		}

		// Add the html nugget to the body.
		$this->body .= $html;
	}

	// This prints out what we have.
	public function flush() {

		// Assemble the html.
		$html = $this->header . $this->body . $this->footer;

		global $IGB;
		global $IGB_VISUAL;
		// Add more spacing for IGB
		if ($IGB && $IGB_VISUAL) {
			$html = str_replace("</table>", "</table><br><br>", $html);
		}

		// Use tidy, if we want to.
		if ($this->useTidy) {
			$config = array (
				'indent' => true,
				'output-xhtml' => true,
				'indent-spaces' => "2",
				'wrap' => 95
			);
			$tidy = new tidy;
			$tidy->parseString($html, $config, 'utf8');
			$tidy->cleanRepair();
			return ($tidy);
		}

		return ($html);
	}
}
?>
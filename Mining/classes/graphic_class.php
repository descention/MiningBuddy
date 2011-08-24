<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/graphic_class.php,v 1.19 2008/01/04 12:32:50 mining Exp $
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

class graphic {

	// Sizes
	private $height;
	private $width;

	// Font
	private $prefixed;
	private $font;
	private $angle;
	private $fontsize;

	// Image & Colors
	private $image;
	private $bgcolor;
	private $textcolor;
	private $rawColor;
	private $rawBGColor;

	// Text
	private $text;

	// Modes
	private $mode;
	private $returnHtml;
	private $type;
	private $direct;
	private $secondPass;

	// Create a basic image.
	public function __construct($type, $mode = "db") {

		global $IS_BETA;
		global $DB;
		// Solving the mysterium:
		if (!is_object($DB)) {
			die("No DB object found in cacheImage: Assertion failed! Please inform the project admin of this!");
		}

		switch ($type) {
			case ("heading") :
				$this->height = 50;
				$this->width = 200;
				$this->prefixed = false;
				$this->font = "hall";
				$this->angle = 0;
				$this->fontsize = 18;
				break;

			case ("title") :
				$this->height = 40;
				$this->width = 450;
				$this->prefixed = false;
				$this->font = "hall";
				$this->angle = 0;
				$this->fontsize = 19;
				break;

			case ("menuhead") :
				$this->height = 20;
				$this->width = 150;
				$this->prefixed = false;
				$this->font = "sb";
				$this->angle = 0;
				$this->fontsize = 10;
				break;

			case ("standard") :
				$this->text = "Empty Image";
				$this->height = 20;
				$this->width = 150;
				$this->fontsize = 10;
				$this->font = "sans";
				$this->prefixed = true;
				break;

			default :
				die("invalid type in graphic.class");
				break;
		}

		// Common for all
		$this->image = false;
		$this->bgcolor = $this->convert("222244");
		if (!$IS_BETA) {
			$this->textcolor = $this->convert("ffffff");
			$this->rawColor = "ffffff";
		} else {
			$this->textcolor = $this->convert("ff0000");
			$this->rawColor = "ff0000";
		}
		$this->mode = $mode;
		$this->returnHtml = true;
		$this->type = $type;
		$this->rawBGColor = "222244";
		$this->direct = false;
		$this->secondPass = false;
	}

	// Set background color
	public function setBGColor($color) {
		$this->bgcolor = $this->convert($color);
		$this->rawBGColor = "$color";
	}

	// Set wether we want <img src="pic.png"> or just pic.png
	public function setReturnHtml($bool) {
		$this->returnHtml = $this-> $bool;
	}

	// Set image dimensions
	public function setDimensions($height, $width) {
		$this->height = $height;
		$this->width = $width;
	}

	// Set wether we gonna prefix it or not.
	public function setPrefixed($bool) {
		$this->prefixed = $bool;
	}

	// Set wether we want to cache this or just to the browser.
	public function setDirect($bool) {
		$this->direct = $bool;
	}

	// If we are direct (non cache), this is the flag to print the image.
	public function setSecondPass($bool) {
		$this->secondPass = $bool;
	}

	// Set font
	public function setFont($font) {
		$this->font = $font;
	}

	// Set the text angle
	public function setAngle($angle) {
		$this->angle = $angle;
	}

	// set the text color
	public function setTextColor($color) {
		$this->textcolor = $this->convert($color);
		$this->rawColor = "$color";
	}

	// Set the text
	public function setText($text) {
		$this->text = $text;
	}

	// Render the image.
	public function render() {

		if (!$this->direct) {

			// Add a nice prefix.	
			if ($this->prefixed) {
				$this->text = " >" . $this->text;
			}
			// Check the database (image already cached?)
			$cache = $this->checkCache($this->text);
			global $DOMAIN;
			global $IS_BETA;

			if ($cache == 0) {

				$filename = rand(1111111111, 9999999999);

				// Save colors before objectinizing.
				$textcolor = $this->textcolor;
				$bgcolor = $this->bgcolor;

				// Render image!
				$this->image = ImageCreate($this->width, $this->height);
				$this->bgcolor = ImageColorAllocate($this->image, $this->bgcolor[0], $this->bgcolor[1], $this->bgcolor[2]);
				$this->textcolor = ImageColorAllocate($this->image, $this->textcolor[0], $this->textcolor[1], $this->textcolor[2]);
				if ($this->type == "title") {
					ImageTTFText($this->image, $this->fontsize, $this->angle, 5, ($this->height - 10), $this->textcolor, "./include/fonts/" . $this->font . ".ttf", $this->text);
					if ($IS_BETA) {
						ImageTTFText($this->image, 6, $this->angle, 5, ($this->height - 32), $this->textcolor, "./include/fonts/" . $this->font . ".ttf", "- BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA ");
						ImageTTFText($this->image, 6, $this->angle, 5, ($this->height), $this->textcolor, "./include/fonts/" . $this->font . ".ttf", "- BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA - BETA ");
					}

				} else {
					ImageTTFText($this->image, $this->fontsize, $this->angle, 5, ($this->height - 5), $this->textcolor, "./include/fonts/" . $this->font . ".ttf", $this->text);
				}
				ImagePNG($this->image, "./images/cache/" . $DOMAIN . "/" . $filename . ".png");

				// inform the database that we have just cached a new image.
				if (!file_exists("./images/cache/" . $DOMAIN . "/" . $filename . ".png")) {
					// Whoops! Error saving image to disk!
					die("Error saving rendered image. Is ./images/cache/" . $DOMAIN . " writable by the user?");
				} else {
					// Inform the database now.
					global $DB;
					$DB->query("INSERT INTO images (id, text, type, textColor, bgColor, width, height) values (?,?,?,?,?,?,?)", array (
						"$filename",
						base64_encode($this->text
					), $this->type, $this->rawColor, $this->rawBGColor, $this->width, $this->height));

					// Return the image
					if ($this->returnHtml) {
						return ("<img width=\"" . $this->width . "\" height=\"" . $this->height . "\" border=\"0\" src=\"./images/cache/" . $DOMAIN . "/" . $filename . ".png\">");
					} else {
						return ($filename . ".png");
					}
				}

			} else {
				// We already have the image, return it.
				if ($this->returnHtml) {
					return ("<img width=\"" . $this->width . "\" height=\"" . $this->height . "\" border=\"0\" src=\"./images/cache/" . $DOMAIN . "/" . $cache . ".png\">");
				} else {
					return ($cache . ".png");
				}
			}
		} else {
			// Direct output to browser;
			if ($this->secondPass) {
				// This is the second pass: create the image.
				$this->image = ImageCreate($this->width, $this->height);
				$this->bgcolor = ImageColorAllocate($this->image, $this->bgcolor[0], $this->bgcolor[1], $this->bgcolor[2]);
				$this->textcolor = ImageColorAllocate($this->image, $this->textcolor[0], $this->textcolor[1], $this->textcolor[2]);
				ImageTTFText($this->image, $this->fontsize, $this->angle, 5, ($this->height - 5), $this->textcolor, "./include/fonts/" . $this->font . ".ttf", $this->text);
				ImagePNG($this->image);
			} else {
				// First pass:
				$rand = rand(11111, 99999);
				$prefs[type] = "standard";
				$prefs[text] = $this->text;
				$prefs[bgcolor] = $this->rawBGColor;
				$prefs[color] = $this->rawColor;
				$prefs[prefixed] = $this->prefixed;
				$prefs[id] = $rand;
				$_SESSION["img_$rand"] = base64_encode(serialize($prefs));

				$url = "index.php?image=$rand";
				return ("<img width=\"" . $this->width . "\" height=\"" . $this->height . "\" border=\"0\" src=\"$url\">");
			}

		}
	}

	/* Converts html color code into hex */
	private function convert($html) {
		// Split the chunk up.
		$splitted = explode(".", chunk_split($html, 2, "."));

		// Convert each color
		foreach ($splitted as $split) {
			$converted[] = base_convert($split, 16, 10);
		}

		// Return converted colors
		return ($converted);
	}

	/* Checks wether the image is already in the database */
	private function checkCache($text) {
		// We need access to the database.
		//		return (false);
		global $DB;

		// Base64 encode the image text.
		$text = base64_encode($text);

		// Get the ID.
		$count = $DB->getCol("SELECT id FROM images WHERE text='$text' " .
		"AND type='" . $this->type . "' " .
		"AND textColor='" . $this->rawColor . "' " .
		"AND bgColor='" . $this->rawBGColor . "' " .
		"AND width='" . $this->width . "' " .
		"AND height='" . $this->height . "'");

		// Return the id.
		return ($count[0]);
	}

}
?>
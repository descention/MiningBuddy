<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/profile_class.php,v 1.2 2008/01/06 14:19:35 mining Exp $
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

class profile {

	// Variable scope.
	private $ID;
	private $smallPicUrl;
	private $largePicUrl;
	private $profileDB;
	private $DB;
	private $minerFlag;
	private $haulerFlag;
	private $fighterFlag;
	private $emailVisible;
	private $about;
	private $username;
	private $isOwn;

	// Constructor.
	public function __construct($ID) {

		// Link the DB.
		global $DB;
		$this->DB = & $DB;
		
		// Link the MySelf object.
		global $MySelf;
		$this->MySelf = & $MySelf;

		// Set the ID.
		$this->ID = sanitize($ID);
		numericCheck($this->ID, 0);

		// Set the picture links.
		$this->setImageLinks();

		// Load the profile.
		$this->getProfileDB();
		
		// is it out own profile?
		if ($MySelf->getID() == $this->ID) {
			$this->isOwn = true;
		}

		// Set some vars.
		$this->minerFlag = $this->profileDB[isMiner];
		$this->haulerFlag = $this->profileDB[isHauler];
		$this->fighterFlag = $this->profileDB[isFighter];
		$this->emailVisible = $this->profileDB[emailVisible];
		$this->about = $this->profileDB[about];
	}

	// This sets the picture URLS.
	private function setImageLinks() {
		// Load the API for the user.
		$api = new api($this->ID);

		// Set/Get the image.
		if ($api->valid()) {
			$this->smallPicUrl = "<img src=\"https://image.eveonline.com/Character/" . $api->getCharacterID() . "_64.jpg\">";
			$this->largePicUrl = "<img src=\"https://image.eveonline.com/Character/" . $api->getCharacterID() . "_256.jpg\">";
		}
	}

	// This loads the current profile from the database.
	private function getProfileDB() {
		// Load from the database.
		global $DB;
		$DS = $DB->query("SELECT * FROM profiles WHERE userid='" . $this->ID . "' LIMIT 1");

		// Check for profile
		if ($DS->numRows() == 0) {
			// No profile exists.
			$this->createProfile();
		} else {
			// Store the result.
			$this->profileDB = $DS->fetchRow();
		}
	}

	// This creates a new, empty profile.
	private function createProfile() {
		// Create empty profile.
		$this->DB->query("INSERT INTO profiles (userid) VALUES ('" . $this->ID . "')");
	}

	// Sets the Miner flag.
	public function setMiner($var) {
		// Make it SQL safe.
		if ($var) {
			$var = '1';
		} else {
			$var = '0';
		}

		// Update the database.
		$this->DB->query("UPDATE profiles SET isMiner='" . sanitize($var) . "' WHERE userid='" . $this->ID . "' LIMIT 1");
	}

	// Sets the Hauler flag.
	public function setHauler($var) {
		// Make it SQL safe.
		if ($var) {
			$var = '1';
		} else {
			$var = '0';
		}

		// Update the database.
		$this->DB->query("UPDATE profiles SET isHauler='" . sanitize($var) . "' WHERE userid='" . $this->ID . "' LIMIT 1");
	}

	// Sets the Fighter flag.
	public function setFighter($var) {
		// Make it SQL safe.
		if ($var) {
			$var = '1';
		} else {
			$var = '0';
		}

		// Update the database.
		$this->DB->query("UPDATE profiles SET isFighter='" . sanitize($var) . "' WHERE userid='" . $this->ID . "' LIMIT 1");
	}

	// Sets if the email is shown.
	public function setEmailShown($var) {
		// Make it SQL safe.
		if ($var) {
			$var = '1';
		} else {
			$var = '0';
		}

		// Update the database.
		$this->DB->query("UPDATE profiles SET emailVisible='" . sanitize($var) . "' WHERE userid='" . $this->ID . "' LIMIT 1");
	}

	// Sets the about.
	public function setAbout($about) {
		// Update the database.
		$this->DB->query("UPDATE profiles SET about='" . sanitize($about) . "' WHERE userid='" . $this->ID . "' LIMIT 1");
	}

	// This will return a small or large image.
	public function getImage($type) {
		switch($type){
			case("small"):
				return($this->smallPicUrl);
			break;
			
			case("large"):
				return($this->largePicUrl);
			break;
			
			default:
				makeNotice("Picture type must be either small or large!", "error", "Internal Error");
			break;
		}
	}
	
	// Getters for the variables.
	public function MinerFlag() {
		return ($this->minerFlag);
	}
	
	public function HaulerFlag() {
		return ($this->haulerFlag);
	}
	
	public function FighterFlag() {
		return ($this->fighterFlag);
	}
	
	public function EmailVisible() {
		return($this->emailVisible);
	}
	
	public function GetAbout(){
		return ($this->about);
	}
	
	public function isOwn(){
		return ($this->isOwn);
	}

}
?>
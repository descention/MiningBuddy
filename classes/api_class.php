<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/api_class.php,v 1.32 2008/10/22 12:15:15 mining Exp $
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

class api {

	// Variables needed
	private $UserID;
	private $DB;
	private $api_key;
	private $api_id;
	private $valid;
	private $validatedOn;
	private $characterID;
	private $nextValidation;
	private $dontValidate;

	// Constructor: Load store API key and initialize.
	public function __construct($userID, $dontvalidate = false) {
        require_once 'vendor/autoload.php';
		// sanitiy check
		if ($userID < 0 || !is_numeric($userID)) {
			makeNotice("$userID is not numeric (API class)", "error", "API Error! (internal)");
		}

		$this->dontValidate = $dontvalidate;

		// Store userID.
		$this->UserID = $userID;

		// Link DB
		global $DB;
		$this->DB = & $DB;

		// Ask the oracle.
		$myKeyDB = $this->DB->query("SELECT * FROM api_keys WHERE userid ='$userID' LIMIT 1");

		// Did we find an api key?
		if ($myKeyDB->numRows() != 1) {
			// No API Key found
			return (false);
		} else {
			// API Key found
			$myKeyDB = $myKeyDB->fetchRow();
			$this->api_key = $myKeyDB['apiKey'];
			$this->api_id = $myKeyDB['apiID'];
			$this->valid = $myKeyDB['api_valid'];
			$this->validatedOn = $myKeyDB['time'];

			// Calculate when next verification should occur.
			global $TIMEMARK;
			$nextCheck = getConfig("api_keys_valid") * 86400;

			// the minimum time is 1 day.
			if ($nextCheck < 86400) {
				$nextCheck = 86400;
			}

			// Set next validation time.
			$this->nextValidation = $nextCheck + $this->validatedOn;

			// Check if verification should occur.
			if ($this->nextValidation >= $TIMEMARK) {
				// API key still valid.
				$this->valid = true;
			} else {
				// API key expired.
				if (!$this->dontValidate) {
					$this->authorizeApi();
				}
			}

			$this->characterID = $myKeyDB['charid'];
			return (true);
		}
	}

	// Set (a new) API Key.	
	public function setApiKey($apiID, $apiKey) {
		// Wash it.
		$apiID = sanitize($apiID);
		$apiKey = sanitize($apiKey);

		// Get the time.
		global $TIMEMARK;

		// Delete possible current key(s).
		$this->deleteApiKey();

		// Insert new row.
		$this->DB->query("INSERT INTO api_keys (userid, api_keys.time, apiID, apiKey, api_valid) VALUES (?,?,?,?,?)", array (
			$this->UserID,
			0,
			$apiID,
			$apiKey,
			0
		));

		// Success?
		if ($this->DB->affectedRows() == 1) {
			// Yes!
			// Authorize the API
			//			$this->authorizeApi();
			return (true);
		} else {
			// NO :/
			makeNotice("The API key could not be stored into the database!", "error", "Internal Error");
		}
	}

	// Deletes the api key.
	public function deleteApiKey() {
		$this->DB->query("DELETE FROM api_keys WHERE userid = '" . $this->UserID . "'");
	}

	// The main jiggamahig: allows fetching or key and id.
	public function getApiKey() {
		return ($this->api_key);
	}

	public function getApiID() {
		return ($this->api_id);
	}

	public function valid() {
		return ($this->valid);
	}

	public function getCharacterID() {
		return ($this->characterID);
	}

	public function validatedOn() {
		return ($this->validatedOn);
	}

	public function nextValidation() {
		return ($this->nextValidation);
	}

	public function authorizeApi() {
		global $MySelf;

		// Handle not-logged in shizms.
		if (!is_object($MySelf)) {
			return (false);
		}

        $pheal = new Pheal($this->api_id, $this->api_key, "");

        $result = $pheal->Characters();

        /*
         * Multiple character shizms
         * We have to loop through all character names until
         * we find the one that matches the username.
         */
        foreach($result->characters as $character){
            $api_char = strtolower($character->name);
            if($api_char == strtolower($MySelf->getUsername())){
                $api_id = $character->characterID;
                $found = true;
            }
        }

        // Set the key as valid in the api-database.
        if (($api_char != "") && ($found == true) && ($api_id > 0)) {
            // Set the valid bit and the charID in the database.
            global $TIMEMARK;
            $this->DB->query("UPDATE api_keys SET api_valid ='1', charid ='" . $api_id . "', time = '" . $TIMEMARK . "' WHERE userid = '" . $MySelf->getID() . "' LIMIT 1");

            // Check for success.
            if ($this->DB->affectedRows() == 1) {
                // Failure!
                $this->valid = true;
                $this->validatedOn = $TIMEMARK;
                $this->characterID = $api_id;
            } else {
                makeNotice("Your API key could not been verified! Your character name " . ucfirst(stripslashes($api_char)) . " needs to be the same as your login account, which is " . $MySelf->getUsername() . ". Upper and lowercase does not matter.", "error", "API update failure");
            }

        }

	}
}
?>
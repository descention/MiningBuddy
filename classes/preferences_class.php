<?PHP


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/preferences_class.php,v 1.7 2008/01/06 19:41:42 mining Exp $
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

class preferences {

	private $preferences;
	private $UserID;

	public function __construct($id) {
		/*
		 * The constructor.
		 */
		if (!is_numeric($id) || $id < 0) {
			makeNotice("Invalid UserID given; cant construct a preference object!", "error", "Nyah! Nyah!");
		}

		$this->UserID = $id;
		$this->preferences = $this->loadPrefs();
		$_SESSION['PREFS'] = serialize($this->preferences);
	}

	public function getPref($pref) {
		/*
		 * getPref will return the current value of the
		 * preference $pref.
		 */
		return ($this->preferences[$pref]);
	}

	public function setPref($pref, $value) {
		/*
		 * setPref will set the value ($value) of $pref,
		 * and call storePrefs.
		 */

		if (empty ($pref)) {
			makeNotice("No preference name given for setting, error in preference_class", "error", "Nyah! Nyah!");
		}

		// Set the preference.
		$this->preferences[$pref] = "$value";
		$this->storePrefs();
	}

	public function storePrefs() {
		/*
		 * storePrefs will serialize the preferences array
		 * into a string and store it in the database.
		 */

		// Database shizms! :)
		global $DB;

		// Serialize the object, so MySQL wont choke.
		$serializedPrefs = serialize($this->preferences);

		// And store the string.
		$DB->query("UPDATE users SET preferences = '" . $serializedPrefs . "' where ID='" . $this->UserID . "'");

		// Update the session, too.
		$_SESSION['PREFS'] = serialize($this->preferences);
	}

	private function loadPrefs() {
		/*
		 * loadPrefs will load a previously saved, serialized
		 * array back into PHP, unserialize and set it.
		 * On error or if no previously saved array has been
		 * found, it calls genPrefs.
		 */

		// Can we used cached data?
		if (!empty ($_SESSION['PREFS'])) {
			$TEMP = unserialize($_SESSION['PREFS']);
			if (is_array($TEMP)) {
				return ($TEMP);
			}
		}

		// Load some globals.
		global $DB;
		global $MySelf;

		// Fetch the string.
		$DS = $DB->query("SELECT preferences FROM users WHERE ID='" . $this->UserID . "' AND deleted='0' LIMIT 1");

		// Did we fish something?
		if ($DB->numRows() == 1) {
			// ok, found the row, but is there something in it?
			$serializedPrefs = $DS->fetchRow();
			$prefs = unserialize($serializedPrefs['preferences']);

			if (!empty ($prefs)) {
				// Row found, and prefs are not empty.
				return ($prefs);
			}
		}
		// No preferences found, lets generate new ones.
		return ($this->genPrefs());
	}

	private function genPrefs() {
		/*
		 * genPrefs is called when loadPrefs could not find
		 * any previously saved data. genPrefs will then
		 * create a new array with reasonably default values. 
		 */
		$pref = array (
			"CanAddCans" => "1",
			"CanMyCans" => "1",
			"CanRunCans" => "1",
			"CanAllCans" => "1",
			"CanForRun" => "1",
			"CanNaming" => "1",
			"sirstate" => "1"
		);

		// We are just generating here. No touchies.
		return ($pref);
	}

}

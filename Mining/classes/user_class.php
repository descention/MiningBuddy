<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/user_class.php,v 1.35 2008/09/08 09:04:17 mining Exp $
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

class user {

	// Declaration of object variables.
	private $id; // Internal UserID (number)
	private $username; // Username
	private $email; // Email
	private $rank; // Rank (int)
	private $rankName; // Rank (string)
	private $lastlogin; // Int: UNIX seconds: Time of last login
	private $confirmed; // Bool: Wether an Admin confirmed this account
	private $emailvalid; // Bool: Wherever the user verified his email
	private $optInState; // Wether the user wants to recive emails or not.
	private $logintime; // Stores the current time of the login.

	// Permissions Matrix
	private $canLogin; // Bool: Can user log in?
	private $canJoinRun; // Bool: Can user join runs?
	private $canCreateRun; // Bool: Can user create new runs?
	private $canCloseRun; // Bool: Can user close runs ?
	private $canAddHaul; // Bool: Can user haul to an opp?
	private $canChangePwd; // Bool: Is user allowed to change his password?
	private $canChangeEmail; // Bool: Is user allowed to change his email?
	private $canChangeOre; // Bool: Is user allowed to change ore settings?
	private $canAddUser; // Bool: Is user allowed to add new users?
	private $canSeeUsers; // Bool: Is user allowed to see other user accounts?
	private $canDeleteUser; // Bool: Is user allowed to delete users from database?
	private $canEditRank; // Bool: Is user allowed to edit other peoples rank?
	private $canManageUser; // Bool: Is user allowed to edit users?
	private $canSeeEvents; // Bool: Is user allowed to see events in the database?
	private $canEditEvents; // Bool: Is user allowed to add and edit events?
	private $canDeleteEvents; // Bool: Is user allowed to add and edit events?
	private $canDeleteRun; // Bool: Is user allowed to delete runs?
	private $isLottoOfficial; // Bool: Can create, delete and run groups and drawings.
	private $canPlayLotto; // Bool: User can play lotto?
	private $isOfficial; // Bool: Is user an official capacity?
	private $isAdmin; // Bool: Is the user an admin?
	private $isAccountant; // Bool: Are we an accountant?
	
	// Constructor, Needs a database row (user) and the current time.
	public function __construct($dro, $mark) {

		if ($dro && $mark) {
			// General info
			$this->id = $dro[id];
			$this->username = $dro[username];
			$this->email = $dro[email];
			$this->rank = $dro[rank];
			$this->lastlogin = $dro[lastlogin];
			$this->confirmed = $dro[confirmed];
			$this->emailvalid = $dro[emailvalid];
			$this->isOfficial = $dro[isOfficial];
			$this->canDeleteRun = $dro[canDeleteRun];
			$this->logintime = $mark;

			// Rank Goodness.			
			global $DB;
			$tempRank = $DB->getCol("SELECT name FROM ranks WHERE rankid='" . $this->rank . "' LIMIT 1");
			if (empty ($tempRank[0])) {
				$this->rankName = "(no rank)";
			} else {
				$this->rankName = $tempRank[0];
			}

			// Permissions
			$this->canLogin = $dro[canLogin];
			$this->canJoinRun = $dro[canJoinRun];
			$this->canCreateRun = $dro[canCreateRun];
			$this->canCloseRun = $dro[canCloseRun];
			$this->canAddHaul = $dro[canAddHaul];
			$this->canChangePwd = $dro[canChangePwd];
			$this->canChangeEmail = $dro[canChangeEmail];
			$this->canChangeOre = $dro[canChangeOre];
			$this->canAddUser = $dro[canAddUser];
			$this->canSeeUsers = $dro[canSeeUsers];
			$this->canDeleteUser = $dro[canDeleteUser];
			$this->canEditRank = $dro[canEditRank];
			$this->canManageuser = $dro[canManageUser];
			$this->canSeeEvents = $dro[canSeeEvents];
			$this->canDeleteEvents = $dro[canDeleteEvents];
			$this->canEditEvents = $dro[canEditEvents];
			$this->canPlayLotto = $dro[canPlayLotto];
			$this->isLottoOfficial = $dro[isLottoOfficial];
			$this->optInState = $dro[optIn];
			$this->isAccountant = $dro[isAccountant];
			$this->isAdmin = $dro[isAdmin];
		} else {
			$this->id = -1;
		}
	}

	// Checks wether the login time has passed.
	public function isValid() {

		global $TIMEMARK;

		if (((getConfig("TTL") * 60) + $this->logintime) > $TIMEMARK) {
			return (1);
		} else {
			return (0);
		}

	}
	
	public function getID() {
		return ($this->id);
	}

	public function getUsername() {
		return ($this->username);
	}

	public function getEmail() {
		return ($this->email);
	}

	public function getRank() {
		return ($this->rank);
	}

	public function getRankName() {
		return ($this->rankName);
	}

	public function optInState() {
		return ($this->optInState);
	}

	public function setOptIn($temp) {
		$this->optInState = $temp;
	}

	public function getLastlogin() {
		return ($this->lastlogin);
	}

	public function isAccountant() {
		return ($this->isAccountant);
	}

	public function getConfirmed() {
		return ($this->confirmed);
	}

	public function getEmailvalid() {
		return ($this->emailvalid);
	}

	public function canLogin() {
		return ($this->canLogin);
	}

	public function canDeleteRun() {
		return ($this->canDeleteRun);
	}

	public function canJoinRun() {
		return ($this->canJoinRun);
	}

	public function canCreateRun() {
		return ($this->canCreateRun);
	}

	public function canCloseRun() {
		return ($this->canCloseRun);
	}

	public function canAddHaul() {
		return ($this->canAddHaul);
	}

	public function canSeeEvents() {
		return ($this->canSeeEvents);
	}

	public function canDeleteEvents() {
		return ($this->canDeleteEvents);
	}

	public function canEditEvents() {
		return ($this->canEditEvents);
	}

	public function canChangePwd() {
		return ($this->canChangePwd);
	}

	public function canChangeEmail() {
		return ($this->canChangeEmail);
	}

	public function canChangeOre() {
		return ($this->canChangeOre);
	}

	public function canAddUser() {
		return ($this->canAddUser);
	}

	public function canSeeUsers() {
		return ($this->canSeeUsers);
	}

	public function canDeleteUser() {
		return ($this->canDeleteUser);
	}

	public function canEditRank() {
		return ($this->canEditRank);
	}

	public function canManageUser() {
		return ($this->canEditRank);
	}
	
	public function isLottoOfficial() {
		return ($this->isLottoOfficial);
	}
	
	public function canPlayLotto() {
		return ($this->canPlayLotto);
	}

	public function isOfficial() {
		return ($this->isOfficial);
	}

	public function isAdmin() {
		return ($this->isAdmin);
	}

}
?>
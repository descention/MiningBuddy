<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/transaction_class.php,v 1.5 2008/01/04 12:32:51 mining Exp $
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

class transaction {

	// Declare variables
	private $from; // INT: userid where money originates from / goes to
	private $to; // INT: userid affected
	private $amount; // INT: amount of isk
	private $type; // 0 = deposit, 1 = withdrawal
	private $isTransfer; // bool: 0 = create new money, 1 = deduct money from sender
	private $reason; // String with the reason.

	// Constructor
	public function __construct($to, $type, $amount) {
		// We need some more globals at this stage
		global $MySelf;

		// Check for validity..
		numericCheck($to);
		numericCheck($amount, 1);
		numericCheck($type, 0, 1);

		// .. and set the variables.
		$this->to = $to;
		$this->type = $type;

		// In case of a withdrawal, -*1 the amount.
		if ($type == 1) {
			$this->amount = ($amount * -1);
		} else {
			$this->amount = $amount;
		}

		// Define standard content for remaining variables.
		$this->isTransfer = false;
		$this->from = $MySelf->getID();
	}

	// Make this a transfer.
	public function isTransfer($bool) {
		if ($bool) {
			$this->isTransfer = 1;
		} else {
			$this->isTransfer = 0;
		}
	}

	// Set the reason.
	public function setReason($reason) {
		// Cut it down to 500 chars. 		
		$this->reason = substr($reason, 0, 499);
	}

	// Make the transfer.
	public function commit() {
		// Indeed, we need the database.
		global $DB;
		global $TIMEMARK;

		// Do the transfer. 		
		$DB->query("INSERT INTO transactions (owner, banker, type, amount, reason, time) VALUES (?,?,?,?,?,?)", array (
			$this->to,
			$this->from,
			$this->type,
			$this->amount,
			$this->reason,
			$TIMEMARK
		));
		// Set true/1 on success.
		$status = $DB->affectedRows();

		// On success, and if this is a transaction, do the counterpart now.
		if ($status == 1 && $this->isTransfer) {
			$DB->query("INSERT INTO transactions (owner, banker, type, amount, reason, time) VALUES (?,?,?,?,?,?)", array (
				$this->from,
				$this->from,
				 (1 - $this->type
			), ($this->amount * -1), $this->reason, $TIMEMARK));
			$status = $DB->affectedRows();
		}

		// If one/both status are true, return just that.
		if ($status) {
			return ($status);
		} else {
			return ($status);
		}
	}

}
?>
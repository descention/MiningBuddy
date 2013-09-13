<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/getLogins.php,v 1.1 2008/01/03 14:55:10 mining Exp $
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

function getLogins($user) {

	// Sanity
	global $DB;
	global $MySelf;
	numericCheck($user, 0);

	if (isset ($_GET['loginPage'])) {
		numericCheck($_GET['loginPage']);
	}

	// ask the oracle.
	$logins = $DB->getCol("SELECT COUNT(authkey) as count FROM auth WHERE user='" . $user . "'");
	$logins_count = $logins[0];
	$logins_pages = ceil(($logins_count / 20));

	// No transactions yet.
	if ($logins_count < 1) {
		return (false);
	}

	if(isset($_GET['loginPage'])){
		$currentPage = $_GET['loginPage'];
	}else{
		$currentPage = 0;
	}

	// Get the right amount of datasets from the dbase.
	if ($currentPage > 0 && is_numeric($currentPage)) {
		$min = (20 * $currentPage) - 20;
	} else {
		$min = 0;
	}

	// Query the database accordingly

	// Show all logins.
	$loginDS = $DB->query("SELECT * from auth where user = '" . $user . "' ORDER BY issued DESC LIMIT $min,20");

	$login_table = new table(3, true);
	$login_table->addHeader(">> " . ucfirst(idToUsername($user)) . "'s recent logins");
	$login_table->addRow("#060622");
	$login_table->addCol("Time / Date", array (
		"bold" => true
	));
	$login_table->addCol("From IP", array (
		"bold" => true
	));
	$login_table->addCol("Useragent", array (
		"bold" => true
	));

	// Create a row for each login.
	while ($row = $loginDS->fetchRow()) {
		$login_table->addRow();
		$login_table->addCol(date("d.m.y H:i", $row['issued']));
		$login_table->addCol($row['ip']);
		$login_table->addCol(substr($row['agent'], 0, 60) . "...");
		$haveLogins = true;
	}

	// if we have more than 1 page, show the navbar.
	if ($logins_pages > 1) {
		// Handle first page: Static numbering.
		if ($currentPage < 1) {
			$next = 2;
		} else {
			// handle pages greater 2. Check if we have yet another page.
			if ($logins_pages > ($currentPage)) {
				$next = $currentPage +1;
			}
			// All pages above 2 have a previous page.
			$prev = $currentPage -1;
		}

		$login_table->addRow("#060622");

		// Show backlink, unless we are at page 1.
		if ($prev) {
			// We have a previous page (at page > 1)
			$login_table->addCol("<a href=\"index.php?action=$_GET[action]&id=$_GET[id]&loginPage=$prev\">prev</a>", array (
				"align" => "left",
				"width" => "20%"
			));
		} else {
			// No previos page (at page 1);
			$login_table->addCol(" ", array (
				"width" => "20%"
			));
		}

		// Empty cell, where direct links used to be.
		$login_table->addCol(" ");

		// Next link
		if ($currentPage < $logins_pages) {
			// We have a next page. (at page < n)
			$login_table->addCol("<a href=\"index.php?action=$_GET[action]&id=$_GET[id]&loginPage=$next\">next</a>", array (
				"align" => "right"
			));
		} else {
			// This was the last page. (at page n)
			$login_table->addCol(" ");
		}

		// Show direct page links.
		if ($logins_pages > 1) {
			for ($i = 1; $i <= $logins_pages; $i++) {
				if ($currentPage == $i) {
					$text .= "[$i]";
				} else {
					$action = isset($_GET['action'])?$_GET['action']:"";
					$id = isset($_GET['id'])?$_GET['id']:"";
					$text .= "[<a href=\"index.php?action=$action&id=$id&loginPage=$i\">$i</a>] ";
				}
			}
			$login_table->addRow("#060622");
			$login_table->addCol($text, array (
				"colspan"=>"3",
				"align"=>"center"
			));
		}
	}

	// Return the html table.
	return ($login_table->flush());
}
?>
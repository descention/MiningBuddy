<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/modConfiguration.php,v 1.11 2008/10/22 12:15:15 mining Exp $
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

function modConfiguration() {

	// The usual suspects.
	global $DB;
	global $MySelf;
	
	// Use this to trac if something has change in Market Settings
	$marketSettingChanged = false;

	// Are we allowed to be here?
	if (!$MySelf->isAdmin()) {
		// I think not!
		makeNotice("You Are not allowed to do this. CONCORD has been informed.", "warning", "Warp scrambled");
	}

	// Edit sitename.
	setConfig("sitename", sanitize($_POST['sitename']));

	// Ban goodness.
	if ($_POST[banAttempts] >= 5 && is_numeric($_POST['banAttempts'])) {
		setConfig("banAttempts", "$_POST[banAttempts]");
	}

	if ($_POST[banTime] > 0 && is_numeric($_POST[banTime])) {
		setConfig("banTime", "$_POST[banTime]");
	}

	// Events Module
	if ($_POST[events] == "true") {
		setConfig("events", "1");
	} else {
		setConfig("events", "0");
	}

	// Show advanced options
	if ($_POST[advancedOptions] == "true") {
		setConfig("advancedOptions", "1");
	} else {
		setConfig("advancedOptions", "0");
	}

	// Cargo Container Module
	if ($_POST[cargocontainer] == "true") {
		setConfig("cargocontainer", "1");
	} else {
		setConfig("cargocontainer", "0");
	}
	
	// Lotto Module
	if ($_POST[Lotto] == "true") {
		setConfig("Lotto", "1");
	} else {
		setConfig("Lotto", "0");
	}

	// Default Tax
	if (!empty ($_POST[defaultTax])) {
		numericCheck($_POST[defaultTax], 0, 100);
		setConfig("defaultTax", "$_POST[defaultTax]");
	}

	// Can lifetime
	if (!empty ($_POST[canLifeTime])) {
		numericCheck($_POST[canLifeTime], 0, 15000);
		setConfig("canLifeTime", "$_POST[canLifeTime]");
	}

	// Max Lotto Percent
	if (!empty ($_POST[LottoPercent])) {
		numericCheck($_POST[LottoPercent], 0, 15000);
		setConfig("LottoPercent", "$_POST[LottoPercent]");
	}

	// Session expiration
	if (!empty ($_POST[canLifeTime])) {
		numericCheck($_POST[canLifeTime], 15, 15000);
		setConfig("TTL", "$_POST[TTL]");
	}

	// Time Offset
	if (is_numeric($_POST[timeOffset])) {
		numericCheck($_POST[timeOffset], -12, 12);
		setConfig("timeOffset", "$_POST[timeOffset]");
	}

	// Trust Setting
	if ($_POST[trustSetting] >= 0) {
		numericCheck($_POST[trustSetting], 0, 2);
		setConfig("trustSetting", "$_POST[trustSetting]");
	}

	// API Key validity
	if ($_POST[api_keys]) {
		numericCheck($_POST[api_keys], 0, 30);
		setConfig("api_keys_valid", "$_POST[api_keys]");
	}
	
	// Use Market Values Setting
	if ($_POST[useMarket]) {
		if (getConfig("useMarket") != 1) {
			$marketSettingChanged = true;
		}
		setConfig("useMarket", "1");
	} else {
		if (getConfig("useMarket") != 0) {
			$marketSettingChanged = true;
		}
		setConfig("useMarket", "0");
	}
	
	// Market Region Value Setting
	if (!empty ($_POST[useRegion])) {
		if (getConfig("useRegion") != $_POST[useRegion]) {
			$marketSettingChanged = true;
		}
		setConfig("useRegion", "$_POST[useRegion]");
	}
	
	// Order Type to use Setting
	if (!is_null($_POST[orderType])) {
		if (getConfig("orderType") != $_POST[orderType]) {
			$marketSettingChanged = true;
		}
		setConfig("orderType", "$_POST[orderType]");
	}
	
	// Price Criteria Setting
	if (!empty ($_POST[priceCriteria])) {
		if (getConfig("priceCriteria") != $_POST[priceCriteria]) {
			$marketSettingChanged = true;
		}
		setConfig("priceCriteria", "$_POST[priceCriteria]");
	}

	if ($marketSettingChanged) {
		$DB->query("UPDATE `itemList` SET `updateTime` = 0");
	}

	// All done here!
	makeNotice("New site settings have been saved in database. Please note that you need to relogin to changes to take effect.", "notice", "Update OK", "index.php?action=configuration", "OK");

}
?>

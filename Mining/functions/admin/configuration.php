<?php


/*
* MiningBuddy (http://miningbuddy.net)
* $Header: /usr/home/mining/cvs/mining/functions/admin/configuration.php,v 1.7 2008/05/01 15:37:24 mining Exp $
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

/*
 * Creates a page with all the configuration options for the site.
 */

function configuration() {

	/*
	 * Checks, Setups ETC
	 */

	// You guessed right.
	global $MySelf;
	global $DB;
	$config = array (
		"align" => "right"
	);

	// Are we an admin here?
	if (!$MySelf->isAdmin()) {
		makeNotice("You are not an admin, and are forbidden to be here.", "warning", "What are you sneaking 'round here?");
	}

	/*
	 * Main Site Configuration
	 */

	$table = new table(2, true);
	$table->addHeader(">> Edit configuration");

	// SiteName.
	$table->addRow();
	$table->addCol("Sitename:", $config);
	$table->addCol("<input type=\"text\" name=\"sitename\" size=\"70\" value=\"" . getConfig("sitename", true) . "\">");

	// Session Lifetime
	$table->addRow();
	$table->addCol("Session expiration in minutes:", $config);
	$table->addCol("<input type=\"text\" name=\"TTL\" value=\"" . getConfig("TTL", true) . "\">");

	// Time Offset.
	$currentOffset = getConfig("timeOffset", true);
	// Make the options.
	for ($i = -12; $i <= 12; $i++) {
		// Calculate offset for eve time.
		$eve = date("l, H:i", (date("U") - ($i * 60 * 60)));
		if ("$i" == "$currentOffset") {
			$pdm .= "<option value=\"$i\" selected>EvE: $eve (offset: $i hours) (current)</option>";
		} else {
			$pdm .= "<option value=\"$i\">EvE: $eve (offset: $i hours)</option>";
		}
	}
	$table->addRow();
	$table->addCol("Server time offset:", $config);
	$table->addCol("<select name=\"timeOffset\">" . $pdm . "</select>");
	unset ($pdm);

	// Ban goodness
	$table->addRow();
	$table->addCol("Autoban: Kicks in after ", $config);
	$table->addCol("<input type=\"text\" name=\"banAttempts\" value=\"" . getConfig("banAttempts", true) . "\"> attempts made within 15 minutes.");

	$table->addRow();
	$table->addCol("Autoban: ban holds", $config);
	$table->addCol("<input type=\"text\" name=\"banAttempts\" value=\"" . getConfig("banTime", true) . "\"> minutes.");

	// Can lifetime.
	if (getConfig("cargocontainer", true)) {
		$table->addRow();
		$table->addCol("Can lifetime in minutes:", $config);
		$table->addCol("<input type=\"text\" name=\"canLifeTime\" value=\"" . getConfig("canLifeTime", true) . "\">");
	}

	// Max Lotto tickets %
	if (getConfig("Lotto", true)) {
		$table->addRow();
		$table->addCol("Max Lotto tickets (%):", $config);
		$table->addCol("<input type=\"text\" name=\"LottoPercent\" value=\"" . getConfig("LottoPercent", true) . "\">");
	}

	// Default Tax.
	$table->addRow();
	$table->addCol("Default Corp tax (set to zero for dynamic):", $config);
	$table->addCol("<input type=\"text\" name=\"defaultTax\" value=\"" . getConfig("defaultTax", true) . "\">");

	// Trust, IGB.
	$table->addRow();
	$table->addCol("IGB Trust setting:", $config);
	unset ($pdm);
	$trustSetting = getConfig("trustSetting", true);

	// Preselect the entry.
	if ($trustSetting == 0) {
		$pdm .= "<option selected value=\"0\">Do not trust IGB at all. (disable)</option>";
	} else {
		$pdm .= "<option value=\"0\">Do not trust IGB at all. (disable)</option>";
	}

	if ($trustSetting == 1) {
		$pdm .= "<option selected value=\"1\">Use IGB to prefill forms (enable, safe)</option>";
	} else {
		$pdm .= "<option value=\"1\">Use IGB to prefill forms (enable, safe)</option>";
	}

	if ($trustSetting == 2) {
		$pdm .= "<option selected value=\"2\">Allow fast login using API keys and IGB Trust (enable, somewhat safe)</option>";
	} else {
		$pdm .= "<option value=\"2\">Allow fast login using API keys and IGB Trust (enable, somewhat safe)</option>";
	}

	// Add the pull down menu to the form.
	$table->addCol("<select name=\"trustSetting\">" . $pdm . "</select>");

	// How long should API keys be valid?
	$table->addRow();
	$table->addCol("API keys are valid for:", $config);
	$apiKeysValid = getConfig("api_keys_valid");
	unset ($pdm);
	$pdm = "<select name=\"api_keys\" >";
	for ($i = 1; $i <= 30; $i++) {
		if ($i == 1) {
			// Singular
			if ($i == $apiKeysValid) {
				$pdm .= "<option value=\"$i\" SELECTED >$i day</option>";
			} else {
				$pdm .= "<option value=\"$i\">$i day</option>";
			}
		} else {
			// Plural
			if ($i == $apiKeysValid) {
				$pdm .= "<option value=\"$i\" SELECTED >$i days</option>";
			} else {
				$pdm .= "<option value=\"$i\">$i days</option>";
			}
		}
	}
	$pdm .= "</select>";
	$table->addCol($pdm);

	//$table->addRow();
	//$table->addCol("Advanced Settings:", $config);

	//$advancedOptions = getConfig("advancedOptions", true);
	//if ($advancedOptions) {
	//	$pdm = "<option selected value=\"true\">Online</option>";
	//	$pdm .= "<option value=\"false\">Offline</option>";
	//} else {
	//	$pdm = "<option value=\"true\">Online</option>";
	//	$pdm .= "<option selected value=\"false\">Offline</option>";
	//}
	//$table->addCol("<select name=\"advancedOptions\">" . $pdm . "</select>");
	unset ($pdm);

	// Use Market Values when loading Manage Ore Values
	
	$table->addRow();
	$table->addCol("Use Market for Ore Default Values:", $config);
	
	$useMarket = getConfig("useMarket", true);
	if ($useMarket == 1) {
		$table->addCol("<input name=\"useMarket\" value=\"true\" type=\"checkbox\" checked=\"checked\">");
	}else{
		$table->addCol("<input name=\"useMarket\" value=\"true\" type=\"checkbox\">");	
	}

	if ($useMarket == 1) {
		
		// Select Region to get prices from
		
		$regionDS = $DB->query("SELECT * FROM `eve_Regions` ORDER BY regionName ASC");
		$regionCount = $regionDS->numRows();
		
		$useRegion = getConfig("useRegion", true);
				
		if ($regionCount >= 1) {
			// We have at least 1 region.
			while ($region = $regionDS->fetchRow()) {
				if ($region[regionID] == $useRegion) {
					// The current region is selected.
					$region_pdm .= "<option SELECTED value=\"$region[regionID]\">$region[regionName]</option>";
				} else {
					// The others of course, are not.
					$region_pdm .= "<option value=\"$region[regionID]\">$region[regionName]</option>";
				}
			}
			$regionColumn = "<select name=\"useRegion\">" . $region_pdm . "</select>";
		} else {
			// No regions are in tables.
			$regionColumn = "There are no regions. Region table is empty!";
		}

		$table->addRow();
		$table->addCol("Market Region to use:", $config);
		$table->addCol($regionColumn);
				
		// Select order type to use
		
		$table->addRow();
		$table->addCol("Order type to use:", $config);
		
		$orderType = getConfig("orderType", true);
		if ($orderType == 0) {
			$pdm .= "<option selected value=\"0\">Buy</option>";
		} else {
			$pdm .= "<option value=\"0\">Buy</option>";
		}

		if ($orderType == 1) {
			$pdm .= "<option selected value=\"1\">Sell</option>";
		} else {
			$pdm .= "<option value=\"1\">Sell</option>";
		}

		// Add the pull down menu to the form.
		$table->addCol("<select name=\"orderType\">" . $pdm . "</select>");
		unset ($pdm);
				
		// Select Price Criteria 
		
		$table->addRow();
		$table->addCol("Price Criteria to use:", $config);
		
		$priceCriteria = getConfig("priceCriteria", true);
		if ($priceCriteria == 0) {
			$pdm .= "<option selected value=\"0\">Min</option>";
		} else {
			$pdm .= "<option value=\"0\">Min</option>";
		}
		
		if ($priceCriteria == 1) {
			$pdm .= "<option selected value=\"1\">Max</option>";
		} else {
			$pdm .= "<option value=\"1\">Max</option>";
		}

		if ($priceCriteria == 2) {
			$pdm .= "<option selected value=\"2\">Median</option>";
		} else {
			$pdm .= "<option value=\"2\">Median</option>";
		}

		// Add the pull down menu to the form.
		$table->addCol("<select name=\"priceCriteria\">" . $pdm . "</select>");
		unset ($pdm);
		
	}
		
	// End of table.
	$table->addRow("#060622");
	$table->addCol("All new settings require a relogin to take effect.", array (
		"colspan" => "2"
	));
	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Update configuration\">");

	/*
	 * MODULES
	 */
	$modules_table = new table(2, true);
	$modules_table->addHeader(">> Enable or disable modules");

	// Events enable.
	$modules_table->addRow();
	$modules_table->addCol("Events Module:", $config);

	$eventsState = getConfig("events", true);
	if ($eventsState) {
		$pdm = "<option selected value=\"true\">Online</option>";
		$pdm .= "<option value=\"false\">Offline</option>";
	} else {
		$pdm = "<option value=\"true\">Online</option>";
		$pdm .= "<option selected value=\"false\">Offline</option>";
	}
	$modules_table->addCol("<select name=\"events\">" . $pdm . "</select>");
	unset ($pdm);

	// Cargo Container Module enable.
	$modules_table->addRow();
	$modules_table->addCol("Cargo container Module:", $config);

	$cargocontainer = getConfig("cargocontainer", true);
	if ($cargocontainer) {
		$pdm = "<option selected value=\"true\">Online</option>";
		$pdm .= "<option value=\"false\">Offline</option>";
	} else {
		$pdm = "<option value=\"true\">Online</option>";
		$pdm .= "<option selected value=\"false\">Offline</option>";
	}
	$modules_table->addCol("<select name=\"cargocontainer\">" . $pdm . "</select>");
	unset ($pdm);

	// Lotto Module enable.
	$modules_table->addRow();
	$modules_table->addCol("Lotto Module:", $config);

	$lotto = getConfig("Lotto", true);
	if ($lotto) {
		$pdm = "<option selected value=\"true\">Online</option>";
		$pdm .= "<option value=\"false\">Offline</option>";
	} else {
		$pdm = "<option value=\"true\">Online</option>";
		$pdm .= "<option selected value=\"false\">Offline</option>";
	}
	$modules_table->addCol("<select name=\"Lotto\">" . $pdm . "</select>");
	unset ($pdm);

	// Advanced settings Module Enable.
	$modules_table->addRow();
	$modules_table->addCol("Advanced Settings:", $config);

	$advancedOptions = getConfig("advancedOptions", true);
	if ($advancedOptions) {
		$pdm = "<option selected value=\"true\">Online</option>";
		$pdm .= "<option value=\"false\">Offline</option>";
	} else {
		$pdm = "<option value=\"true\">Online</option>";
		$pdm .= "<option selected value=\"false\">Offline</option>";
	}
	$modules_table->addCol("<select name=\"advancedOptions\">" . $pdm . "</select>");
	unset ($pdm);


	// Assemble form stuff.
	$form = "<form action=\"index.php\" method=\"POST\">";
	$form .= "<input type=\"hidden\" name=\"check\" value=\"true\">";
	$form .= "<input type=\"hidden\" name=\"action\" value=\"configuration\">";

	/*
	 * Templates
	 */

	if (getConfig("advancedOptions")) {
		// Load all templates identifiers (but not the templates to save RAM)
		$templates_DS = $DB->query("SELECT id, identifier, type, descr FROM templates ORDER BY type ASC, identifier");

		// Check if we have some
		if ($templates_DS->numRows() > 0) {
			// Create the table
			$template_table = new table(3, true);
			$template_table->addHeader(">> Edit templates");
			$template_table->addRow("#060622");
			$template_table->addCol("Type");
			$template_table->addCol("Identifier");
			$template_table->addCol("Description");

			// Create a row for every template
			while ($template = $templates_DS->fetchRow()) {
				$template_table->addRow();
				$template_table->addCol($template[type]);
				$template_table->addCol("<a href=\"index.php?action=edittemplate&id=" . $template[id] . "\">" . $template[identifier] . "</a>");
				$template_table->addCol($template[descr]);
			}

			$templates = $template_table->flush();
		}
	}

	// Create the html page.
	$html = "<h2>Edit site configuration</h2>" . $form . $modules_table->flush() . "<br>" . $table->flush() . "<br>" . $templates;

	// We done here, return it!
	return ($html);

}
?>
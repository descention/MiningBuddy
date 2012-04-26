<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeMenu.php,v 1.62 2008/05/01 15:37:24 mining Exp $
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
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT Ssans THE COPYRIGHT
 *  OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 *  TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 *  OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 *  OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

// makeMenu() - creates the clickable menu.

function makeMenu($page = false) {

	// We need some globals.
	global $SITENAME;
	global $IGB;
	global $MySelf;
	global $TIMEMARK;
	global $UPDATE;
	global $IGB_VISUAL;

	$mining_AddHaul = "";
	$mining_canTimer = "";
	$mining_addOp = "";
	$admin_addUser = "";
	$admin_Ore = "";
	$admin_Ships = "";
	$admin_viewUser = "";
	$events_add = "";
	$events_view = "";
	$operationsModule = "";
	$eventsModule = "";
	$lottoModule = "";
	$adminModule = "";
	$pref_emailValid = "";
	
	/*
	 * Mining related Menues.
	 */

	// Create Run
	if ($MySelf->canCreateRun()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$mining_addOp = "[<a href=\"index.php?action=newrun\">Add Op</a>]";
		} else {
			// Using a real browser.
			$mining_addOp = "<a class='menu' href=\"index.php?action=newrun\">&gt; Add Op</a>";
		}
	}

	// Add Haul 
	if ($MySelf->canAddHaul() && userInRun($MySelf->getID())) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$mining_AddHaul = "[<a href=\"index.php?action=addhaul\">Add Haul</a>]";
		} else {
			// Using a real browser.
			$mining_AddHaul = "<a class='menu' href=\"index.php?action=addhaul\">&gt; Add Haul</a>";
		}
	}

	// See all ops link.
	if ($IGB && $IGB_VISUAL) {
		$mining_AllOps = "[<a href=\"index.php?action=list\">List Ops</a>]";
	} else {
		$mining_AllOps = "<a class='menu' href=\"index.php?action=list\">&gt; List Ops</a>";
	}

	// Ore Quotes
	if ($IGB && $IGB_VISUAL) {
		$mining_oreQuotes = "[<a href=\"index.php?action=showorevalue\">Quotes</a>]";
	} else {
		$mining_oreQuotes = "<a class='menu' href=\"index.php?action=showorevalue\">&gt; Quotes</a>";
	}

	// Can timer link
	if (getConfig("cargocontainer")) {
		if ($IGB && $IGB_VISUAL) {
			$mining_canTimer = "[<a href=\"index.php?action=cans\">Can Timer</a>]";
		} else {
			$mining_canTimer = "<a class='menu' href=\"index.php?action=cans\">&gt; Can Timer</a>";
		}
	}

	// Statistics
	if ($IGB && $IGB_VISUAL) {
		$mining_stats = "[<a href=\"index.php?action=globstats\">Statistics</a>]";
	} else {
		$mining_stats = "<a class='menu' href=\"index.php?action=globstats\">&gt; Statistics</a>";
	}
	
	// Hierarchy
	if ($IGB && $IGB_VISUAL) {
		$mining_hier = "[<a href=\"index.php?action=hierarchy\">Hierarchy</a>]";
	} else {
		$mining_hier = "<a class='menu' href=\"index.php?action=hierarchy\">&gt; Hierarchy</a>";
	}

		// Ship Values
	if ($IGB && $IGB_VISUAL) {
		$mining_shipValues = "[<a href=\"index.php?action=showshipvalue\">Ship Values</a>]";
	} else {
		$mining_shipValues = "<a class='menu' href=\"index.php?action=showshipvalue\">&gt; Ship Values</a>";
	}
	
	// Assemble the mining Module link.
	if ($IGB && $IGB_VISUAL) {
		$miningModule = $mining_AllOps . " " . $mining_AddHaul . " " . $mining_canTimer . " " . $mining_oreQuotes . " " . $mining_ShipValues . " " . $mining_addOp . " " . $mining_stats . "";
	} else {
		$miningModule = "<div>";
		$miningModule .= "<img src=\"./images/m-mining.png\">";
		$miningModule .= $mining_AllOps;
		$miningModule .= $mining_AddHaul;
		$miningModule .= $mining_canTimer;
		$miningModule .= $mining_oreQuotes;
		$miningModule .= $mining_shipValues;
		$miningModule .= $mining_addOp;
		$miningModule .= $mining_stats;
		$miningModule .= $mining_hier;
		$miningModule .= "</div><div class='clear'></div>";
	}

	/*
	 * Preferences Stuff
	 */

	// Is our Email validated? 
	if (!$MySelf->getEmailvalid()) {
		// No, its not!
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$pref_emailValid = "[<a href=\"index.php?action=revalidate\">confirm email</a>]";
		} else {
			// Using a real browser.
			$pref_emailValid = "<a class='menu' href=\"index.php?action=revalidate\">&gt; Validate Email</a>";
		}
	}

	if ($IGB && $IGB_VISUAL) {
		$pref_Pref = "[<a href=\"index.php?action=preferences\">Preferences</a>]";
	} else {
		$pref_Pref = "<a class='menu' href=\"index.php?action=preferences\">&gt; Preferences</a>";
	}

	if ($IGB && $IGB_VISUAL) {
		$pref_Prof = "[<a href=\"index.php?action=profile&id=" . $MySelf->getID() . "\">Profile</a>]";
	} else {
		$pref_Prof = "<a class='menu' href=\"index.php?action=profile&id=" . $MySelf->getID() . "\">&gt; Profile</a>";
	}
	
	if ($IGB && $IGB_VISUAL) {
		$pref_Style = "[<a href=\"index.php?action=profile&id=" . $MySelf->getID() . "\">Style</a>]";
	} else {
		$pref_Style = "<a class='menu' href=\"index.php?action=style&id=" . $MySelf->getID() . "\">&gt; Style</a>";
	}

	// Assemble the Preferences module
	if ($IGB && $IGB_VISUAL) {
		$prefModule = $pref_Pref . " " . $pref_Prof . " " . $pref_emailValid . "";
	} else {
		$prefModule = "<div>";
		$prefModule .= "<img border=\"0\" src=\"images/m-preferences.png\">";
		$prefModule .= $pref_Pref;
		$prefModule .= $pref_Prof;
		$prefModule .= $pref_emailValid;
		$prefModule .= $pref_Style;
		$prefModule .= "</div><div class='clear'></div>";
	}

	/*
	 * Logout Stuff
	 */

	if ($IGB && $IGB_VISUAL) {
		// Are we IGB && Passwordless login?
		if ((getConfig("trustSetting") == 2) && $IGB) {
			$logoutModule = "<i>Can not logout due to fast login.</i>";
		} else {
			$logoutModule = "[<a href=\"index.php?auth=logout\">Logout</a>]";
		}
	} else {
		if ((getConfig("trustSetting") == 2) && $IGB) {
			$logoutModule = "";
		} else {
			$logoutModule = "<div>";
			$logoutModule .= "<img src=\"images/m-logout.png\">";
			$logoutModule .= "<a class='menu' href=\"index.php?action=switch\">&gt; Switch Character</a>";
			$logoutModule .= "<a class='menu' href=\"index.php?auth=logout\">&gt; Logout</a>";
			$logoutModule .= "</div><div class='clear'></div>";
			
			
		}
	}

	/*
	 * Administrative Stuff
	 */

	// Are we allowed to change the ore value, edit it? 
	if ($MySelf->canChangeOre()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_Ore = "[<a href=\"index.php?action=changeow\">Edit Items</a>]";
		} else {
			// Using a real browser.
			$admin_Ore = "<a class='menu' href=\"index.php?action=changeow\">&gt; Edit Items</a>";
		}
	}

		// Are we allowed to change the ship value, edit it? 
	if ($MySelf->canChangeOre()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_Ships = "[<a href=\"index.php?action=changesv\">Edit Ships</a>]";
		} else {
			// Using a real browser.
			$admin_Ships = "<a class='menu' href=\"index.php?action=changesv\">&gt; Edit Ships</a>";
		}
	}
	
	// Site configuration
	if ($MySelf->isAdmin()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_site = "[<a href=\"index.php?action=configuration\">Site Settings</a>]";
		} else {
			// Using a real browser.
			$admin_site = "<a class='menu' href=\"index.php?action=configuration\">&gt; Site Settings</a>";
		}
	}

	// Site Maintenance
	if ($MySelf->isAdmin()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_maint = "[<a href=\"index.php?action=maintenance\">Maintenance</a>]";
		} else {
			// Using a real browser.
			$admin_maint = "<a class='menu' href=\"index.php?action=maintenance\">&gt; Maintenance</a>";
		}
	}

	// Manage Ranks
	if ($MySelf->canEditRank()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_ranks = "[<a href=\"index.php?action=showranks\">Edit Ranks</a>]";
		} else {
			// Using a real browser.
			$admin_ranks = "<a class='menu' href=\"index.php?action=showranks\">&gt; Edit Ranks</a>";
		}
	}

	// Are we allowed to view Users?
	if ($MySelf->canSeeUsers()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_viewUser = "[<a href=\"index.php?action=editusers\">List Users</a>]";
		} else {
			// Using a real browser.
			$admin_viewUser = "<a class='menu' href=\"index.php?action=editusers\">&gt; List Users</a>";
		}
	}

	// Are we allowed to 
	if ($MySelf->canAddUser()) {
		// Yeah we are allowed to do this.
		if ($IGB && $IGB_VISUAL) {
			// Browswing in the iGB.
			$admin_addUser = "[<a href=\"index.php?action=newuser\">Add User</a>]";
		} else {
			// Using a real browser.
			$admin_addUser = "<a class='menu' href=\"index.php?action=newuser\">&gt; Add User</a>";
		}
	}

	// Assemble the module.
	if ($admin_addUser || $admin_Ore || $admin_Ships || $admin_viewUser) {
		if ($IGB && $IGB_VISUAL) {
			$adminModule = $admin_site . " " . $admin_ranks . " " . $admin_viewUser . " " . $admin_addUser . " " . $admin_Ore . " " . $admin_Ships . "";
		} else {
			$adminModule = "<div>";
			$adminModule .= "<img src=\"images/m-administration.png\">";
			$adminModule .= $admin_site;
			$adminModule .= $admin_maint;
			$adminModule .= $admin_viewUser;
			$adminModule .= $admin_addUser;
			$adminModule .= $admin_ranks;
			$adminModule .= $admin_Ore;
			$adminModule .= $admin_Ships;
			$adminModule .= "</div><div class='clear'></div>";
		}
	}

	/*
	 * Events Stuff
	 */

	if (getConfig("events")) {
		// Are we allowed to see events?
		if ($MySelf->canSeeEvents()) {
			// Yeah we are allowed to do this.
			if ($IGB && $IGB_VISUAL) {
				// Browswing in the iGB.
				$events_view = "[<a href=\"index.php?action=showevents\">Schedule</a>]";
			} else {
				// Using a real browser.
				$events_view = "<a class='menu' href=\"index.php?action=showevents\">&gt; Schedule</a>";
			}
		}

		// Are we allowed to add events?
		if ($MySelf->canEditEvents()) {
			// Yeah we are allowed to do this.
			if ($IGB && $IGB_VISUAL) {
				// Browswing in the iGB.
				$events_add = "[<a href=\"index.php?action=addevent\">Add Event</a>]";
			} else {
				// Using a real browser.
				$events_add = "<a class='menu' href=\"index.php?action=addevent\">&gt; Add Event</a>";
			}
		}
	}

	// El grande Online Thingy
	if ($IGB && $IGB_VISUAL) {
		$events_OnlineTimer = "[<a href=\"index.php?action=onlinetime\">Online Time</a>]";
	} else {
		$events_OnlineTimer = "<a class='menu' href=\"index.php?action=onlinetime\">&gt; Online Time</a>";
	}

	// Assemblte Events module
		if ($events_add || $events_view) {
			if ($IGB && $IGB_VISUAL) {
				$eventsModule = $events_view . " " . $events_add . " " . $events_OnlineTimer . "";
			} else {
				$eventsModule = "<div>";
				$eventsModule .= "<img src=\"./images/m-events.png\">";
				$eventsModule .= $events_view;
				$eventsModule .= $events_add;
				$eventsModule .= $events_OnlineTimer;
				$eventsModule .= "</div><div class='clear'></div>";
			}
		}

	/*
	 * Wallet stuff
	 */
	if ($IGB && $IGB_VISUAL) {
		$walletModule = "[<a href=\"index.php?action=manageWallet\">Manage Wallet</a>]";
		if ($MySelf->isAccountant()) {
			$walletModule .= " [<a href=\"index.php?action=payout\">Manage Payouts</a>]";
		}
	} else {
		$walletModule = "<div><img src=\"./images/wallet.png\">";
		$walletModule .= "<a class='menu' href=\"index.php?action=manageWallet\">&gt; Manage Wallet</a>";
		if ($MySelf->isAccountant()) {
			$walletModule .= "<a class='menu' href=\"index.php?action=payout\">&gt; Manage Payouts</a>";
		}
		$walletModule .= "</div><div class='clear'></div>";
	}

	/*
	 * Lotto related things.
	 */

	$LOTTO = getConfig("Lotto");
	if ($LOTTO) {
		// Are we allowed to play Lotto?
		if ($MySelf->canPlayLotto()) {
			// Yeah we are allowed to do this.
			if ($IGB && $IGB_VISUAL) {
				// Browswing in the iGB.
				$lotto_Play = "[<a href=\"index.php?action=lotto\">Lotto</a>]";
			} else {
				// Using a real browser.
				$lotto_Play = "<a class='menu' href=\"index.php?action=lotto\">&gt; Lotto</a>";
			}
		}

		if ($MySelf->isLottoOfficial()) {
			// Yeah we are allowed to do this.
			if ($IGB && $IGB_VISUAL) {
				// Browswing in the iGB.
				$lotto_Admin = "[<a href=\"index.php?action=editLotto\">Admin Lotto</a>]";
			} else {
				// Using a real browser.
				$lotto_Admin = "<a class='menu' href=\"index.php?action=editLotto\">&gt; Admin Lotto</a>";
			}
		}

		if ($lotto_Admin || $lotto_Play) {
			// Assemble the Lotto module.
			if ($IGB && $IGB_VISUAL) {
				$lottoModule = $lotto_Play . " " . $lotto_Admin . "";
			} else {
				$lottoModule = "<div>";
				$lottoModule .= "<img src=\"./images/m-lotto.png\">";
				$lottoModule .= $lotto_Play;
				$lottoModule .= $lotto_Admin;
				$lottoModule .= "</div><div class='clear'></div>";
			}
		}

	}

	/*
	 * Open operations Module
	 */
	if ($IGB && $IGB_VISUAL) {
		// tough luck.
	} else {
		$runs = sidebarOpenRuns();
		if ($runs) {
			$operationsModule = "<div>";
			$operationsModule .= "<img src=\"./images/m-runs-in-progress.png\">";
			$operationsModule .= sidebarOpenRuns() . "";
			$operationsModule .= "</div><div class='clear'></div>";
		}
	}

	/*
	 * Show the time.
	 */
	 
	$clockScript = "<script>
	var eveTime = new Date($TIMEMARK*1000);
	eveTime.setHours(eveTime.getHours()-3);
	var eveTimeRefreshRate = 20;// seconds
	var eveTimeZone = '';
	function updateTime(){
		eveTime = new Date(eveTime.getTime()+(eveTimeRefreshRate * 1000));
		minutes = eveTime.getMinutes();
		if(minutes.length < 2){
			minutes = '0' + minutes;
		}
		hours = eveTime.getHours();
		if(hours.length < 2){
			hours = '0' + hours;
		}
		\$('#eveTime').html(hours + ':' + minutes + ' EvE');
		setTimeout('updateTime()', eveTimeRefreshRate * 1000);
	}
	setTimeout('updateTime()', eveTimeRefreshRate * 1000);
	
	$('#menu img').click(function() {
	  $(this).siblings().toggle('slow');
	});
	
	
	</script>";
	$clock = "<b><hr><center id='eveTime'>" . date("H:i", $TIMEMARK) . " EvE</center><hr>$clockScript</b>";
	/*
	 * Assemble the module-block.
	 */
	if ($IGB && $IGB_VISUAL) {
		global $VERSION;
		$menu = new table(2, true, "width=\"99%\"");

		// Add the beta warning for the IGB
		global $IS_BETA;
		if ($IS_BETA) {
			$BETAWARNING = "<font color=\"#ff0000\"> - <b>This is a BETA release! Watch out for bugs!</b></font>";
		}

		// Add the Version bar.
		$menu->addHeader($VERSION . $BETAWARNING);

		// Create the mining Menu.
		$menu->addRow();
		$menu->addCol("Mining  >>", array (
			"align" => "right",
			"bold" => "true"
		));
		$menu->addCol($miningModule);

		if ($events_add || $events_view) {
			$menu->addRow();
			$menu->addCol("Events  >>", array (
				"align" => "right",
				"bold" => "true"
			));
			$menu->addCol($eventsModule);
		}

		// Wallet Menu.
		$menu->addRow();
		$menu->addCol("Wallet  >>", array (
			"align" => "right",
			"bold" => "true"
		));
		$menu->addCol($walletModule);

		// Preferences.
		$menu->addRow();
		$menu->addCol("Preferences  >>", array (
			"align" => "right",
			"bold" => "true"
		));
		$menu->addCol($prefModule);

		// Ore managagement.
		if ($admin_Ore || $admin_viewUser || $admin_addUser) {
			$menu->addRow();
			$menu->addCol("Admin  >>", array (
				"align" => "right",
				"bold" => "true"
			));
			$menu->addCol($adminModule);
		}

		// Logout.
		$menu->addRow();
		$menu->addCol("Exit  >>", array (
			"align" => "right",
			"bold" => "true"
		));
		$menu->addcol($logoutModule);

		$menu->addHeader("Logged in as <font color=\"00aa00\">%%USERNAME%%</font>, Rank: <font color=\"00aa00\">%%RANK%%</font>, $DIV_MENU Credits: <font color=\"00aa00\">%%CREDITS%%</font>.");
		$modules = $menu->flush();
	} else {
		$modules = $UPDATE . $miningModule . $operationsModule . $walletModule . $eventsModule . $lottoModule . $prefModule . $adminModule . $logoutModule . $clock;
	}

	// And return it all.
	if ($page) {
		return (str_replace("%%MENU%%", $modules, $page));
	} else {
		return ($modules);
	}
}
?>
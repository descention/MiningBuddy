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
			$mining_addOp = "<a href=\"index.php?action=newrun\"><img border=\"0\" src=\"images/register-new-op.png\"></a><br>";
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
			$mining_AddHaul = "<a href=\"index.php?action=addhaul\"><img border=\"0\" src=\"images/add-haul.png\"></a><br>";
		}
	}

	// See all ops link.
	if ($IGB && $IGB_VISUAL) {
		$mining_AllOps = "[<a href=\"index.php?action=list\">List Runs</a>]";
	} else {
		$mining_AllOps = "<a href=\"index.php?action=list\"><img border=\"0\" src=\"images/all-runs.png\"></a><br>";
	}

	// Ore Quotes
	if ($IGB && $IGB_VISUAL) {
		$mining_oreQuotes = "[<a href=\"index.php?action=showorevalue\">Quotes</a>]";
	} else {
		$mining_oreQuotes = "<a href=\"index.php?action=showorevalue\"><img border=\"0\" src=\"images/orequotes.png\"></a><br>";
	}

	// Can timer link
	if (getConfig("cargocontainer")) {
		if ($IGB && $IGB_VISUAL) {
			$mining_canTimer = "[<a href=\"index.php?action=cans\">Can Timer</a>]";
		} else {
			$mining_canTimer = "<a href=\"index.php?action=cans\"><img border=\"0\" src=\"images/cantimer.png\"></a><br>";
		}
	}

	// Statistics
	if ($IGB && $IGB_VISUAL) {
		$mining_stats = "[<a href=\"index.php?action=globstats\">Statistics</a>]";
	} else {
		$mining_stats = "<a href=\"index.php?action=globstats\"><img border=\"0\" src=\"images/statistics.png\"></a><br>";
	}
	
	// Hierarchy
	if ($IGB && $IGB_VISUAL) {
		$mining_hier = "[<a href=\"index.php?action=hierarchy\">Hierarchy</a>]";
	} else {
		$mining_hier = "<a href=\"index.php?action=hierarchy\"><img border=\"0\" src=\"images/hierarchy.png\"></a><br>";
	}

		// Ship Values
	if ($IGB && $IGB_VISUAL) {
		$mining_shipValues = "[<a href=\"index.php?action=showshipvalue\">Ship Values</a>]";
	} else {
		$mining_shipValues = "<a href=\"index.php?action=showshipvalue\"><img border=\"0\" src=\"images/shipvalues.png\"></a><br>";
	}
	
	// Assemble the mining Module link.
	if ($IGB && $IGB_VISUAL) {
		$miningModule = $mining_AllOps . " " . $mining_AddHaul . " " . $mining_canTimer . " " . $mining_oreQuotes . " " . $mining_ShipValues . " " . $mining_addOp . " " . $mining_stats . "<br>";
	} else {
		$miningModule = "<img src=\"./images/m-mining.png\"><br>";
		$miningModule .= $mining_AllOps;
		$miningModule .= $mining_AddHaul;
		$miningModule .= $mining_canTimer;
		$miningModule .= $mining_oreQuotes;
		$miningModule .= $mining_shipValues;
		$miningModule .= $mining_addOp;
		$miningModule .= $mining_stats;
		$miningModule .= $mining_hier;
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
			$pref_emailValid = "<a href=\"index.php?action=revalidate\"><img border=\"0\" src=\"images/validate.png\"></a><br>";
		}
	}

	if ($IGB && $IGB_VISUAL) {
		$pref_Pref = "[<a href=\"index.php?action=preferences\">Preferences</a>]";
	} else {
		$pref_Pref = "<a href=\"index.php?action=preferences\"><img border=\"0\" src=\"images/preferences.png\"></a><br>";
	}

	if ($IGB && $IGB_VISUAL) {
		$pref_Prof = "[<a href=\"index.php?action=profile&id=" . $MySelf->getID() . "\">Profile</a>]";
	} else {
		$pref_Prof = "<a href=\"index.php?action=profile&id=" . $MySelf->getID() . "\"><img border=\"0\" src=\"images/profile.png\"></a><br>";
	}

	// Assemble the Preferences module
	if ($IGB && $IGB_VISUAL) {
		$prefModule = $pref_Pref . " " . $pref_Prof . " " . $pref_emailValid . "<br>";
	} else {
		$prefModule = "<br>";
		$prefModule .= "<img border=\"0\" src=\"images/m-preferences.png\"><br>";
		$prefModule .= $pref_Pref;
		$prefModule .= $pref_Prof;
		$prefModule .= $pref_emailValid;
	}

	/*
	 * Logout Stuff
	 */

	if ($IGB && $IGB_VISUAL) {
		// Are we IGB && Passwordless login?
		if ((getConfig("trustSetting") == 2) && $IGB) {
			$logoutModule = "<i>Can not logout due to fast login.</i><br>";
		} else {
			$logoutModule = "[<a href=\"index.php?auth=logout\">logout</a>]<br>";
		}
	} else {
		if ((getConfig("trustSetting") == 2) && $IGB) {
			$logoutModule = "<br>";
		} else {
			$logoutModule = "<br>";
			$logoutModule .= "<img src=\"images/m-logout.png\"><br>";
			$logoutModule .= "<a href=\"index.php?auth=logout\"><img border=\"0\" src=\"images/logout.png\"></a><br>";
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
			$admin_Ore = "<a href=\"index.php?action=changeow\"><img border=\"0\" src=\"images/change-ore-value.png\"></a><br>";
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
			$admin_Ships = "<a href=\"index.php?action=changesv\"><img border=\"0\" src=\"images/change-ship-values.png\"></a><br>";
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
			$admin_site = "<a href=\"index.php?action=configuration\"><img border=\"0\" src=\"images/configuration.png\"></a><br>";
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
			$admin_maint = "<a href=\"index.php?action=maintenance\"><img border=\"0\" src=\"images/maintenance.png\"></a><br>";
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
			$admin_ranks = "<a href=\"index.php?action=showranks\"><img border=\"0\" src=\"images/editranks.png\"></a><br>";
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
			$admin_viewUser = "<a href=\"index.php?action=editusers\"><img border=\"0\" src=\"images/user-management.png\"></a><br>";
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
			$admin_addUser = "<a href=\"index.php?action=newuser\"><img src=\"images/add-user.png\" border=\"0\"></a><br>";
		}
	}

	// Assemble the module.
	if ($admin_addUser || $admin_Ore || $admin_Ships || $admin_viewUser) {
		if ($IGB && $IGB_VISUAL) {
			$adminModule = $admin_site . " " . $admin_ranks . " " . $admin_viewUser . " " . $admin_addUser . " " . $admin_Ore . " " . $admin_Ships . "<br>";
		} else {
			$adminModule = "<br>";
			$adminModule .= "<img src=\"images/m-administration.png\"><br>";
			$adminModule .= $admin_site;
			$adminModule .= $admin_maint;
			$adminModule .= $admin_viewUser;
			$adminModule .= $admin_addUser;
			$adminModule .= $admin_ranks;
			$adminModule .= $admin_Ore;
			$adminModule .= $admin_Ships;
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
				$events_view = "<a href=\"index.php?action=showevents\"><img border=\"0\" src=\"images/scheduled-events.png\"></a><br>";
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
				$events_add = "<a href=\"index.php?action=addevent\"><img border=\"0\" src=\"images/add-event.png\"></a><br>";
			}
		}
	}

	// El grande Online Thingy
	if ($IGB && $IGB_VISUAL) {
		$events_OnlineTimer = "[<a href=\"index.php?action=onlinetime\">Online Time</a>]";
	} else {
		$events_OnlineTimer = "<a href=\"index.php?action=onlinetime\"><img border=\"0\" src=\"images/onlinetime.png\"></a><br>";
	}

	// Assemblte Events module
		if ($events_add || $events_view) {
	if ($IGB && $IGB_VISUAL) {
		$eventsModule = $events_view . " " . $events_add . " " . $events_OnlineTimer . "<br>";
	} else {
		$eventsModule = "<br>";
		$eventsModule .= "<img src=\"./images/m-events.png\"><br>";
		$eventsModule .= $events_view;
		$eventsModule .= $events_add;
		$eventsModule .= $events_OnlineTimer;
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
		$walletModule = "<br><img src=\"./images/wallet.png\"><br>";
		$walletModule .= "<a href=\"index.php?action=manageWallet\"><img border=\"0\" src=\"images/manageWallet.png\"></a><br>";
		if ($MySelf->isAccountant()) {
			$walletModule .= "<a href=\"index.php?action=payout\"><img border=\"0\" src=\"images/manage_payouts.png\"></a><br>";
		}
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
				$lotto_Play = "<a href=\"index.php?action=lotto\"><img border=\"0\" src=\"images/lotto-play.png\"></a><br>";
			}
		}

		if ($MySelf->isLottoOfficial()) {
			// Yeah we are allowed to do this.
			if ($IGB && $IGB_VISUAL) {
				// Browswing in the iGB.
				$lotto_Admin = "[<a href=\"index.php?action=editLotto\">Admin Lotto</a>]";
			} else {
				// Using a real browser.
				$lotto_Admin = "<a href=\"index.php?action=editLotto\"><img border=\"0\" src=\"images/lotto-admin.png\"></a><br>";
			}
		}

		if ($lotto_Admin || $lotto_Play) {
			// Assemble the Lotto module.
			if ($IGB && $IGB_VISUAL) {
				$lottoModule = $lotto_Play . " " . $lotto_Admin . "<br>";
			} else {
				$lottoModule = "<br>";
				$lottoModule .= "<img src=\"./images/m-lotto.png\"><br>";
				$lottoModule .= $lotto_Play;
				$lottoModule .= $lotto_Admin;
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
			$operationsModule = "<br>";
			$operationsModule .= "<img src=\"./images/m-runs-in-progress.png\">";
			$operationsModule .= sidebarOpenRuns() . "<br>";
		}
	}

	/*
	 * Show the time.
	 */
	$clock = "<b><hr><center>" . date("H:i", $TIMEMARK) . " EvE</center><hr></b>";
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
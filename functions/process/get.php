<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/process/get.php,v 1.47 2008/09/08 09:04:17 mining Exp $
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
* process_get() function
* This basically is a huge switch, to decide what page the system should
* render. Its default value (no specific page requested) will return the
* main welcome page. All pages are prefixed with basic html stuff, which
* can be found in html/header.html. Same for the footer.
*/
function process_get() {
	global $page;
	global $MySelf;
	
	$ajax = 0;
	if(!isset($_GET['action'])){
		$_GET['action'] = "";
	}
		switch ($_GET['action']) {

			// Maintenance!
			case ("maintenance") :
				$page = maintenance();
				break;

				// a specific run is requested.
			case ("show") :
				$page = listRun();
				$ajax = 20;
				break;

				// a profile is requested.
			case ("profile") :
				$page = profile();
				break;

				// a profile change is requested.
			case ("modprofile") :
				$page = modProfile();
				break;

				// Admin request to delete an api key (NOT user deleting own!)
			case ("delapi") :
				$page = deleteAPIKey();
				break;
				
				// Quick toggle of login capabilities.
			case ("toggleLogin"):
				$page = toggleLogin();
				break;
				
				// Quick confirm an account.
			case ("quickconfirm"):
				$page = quickConfirm();
				break;

				// Change of eMail requested
			case ("changeemail") :
				$page = makeEmailChangeForm();
				break;

				// Show corp hierarchy
			case ("hierarchy") :
				$page = showHierarchy();
				break;

				// Browser solar Systems
			case ("browse") :
				$page = browser();
				break;

				// User wants to delete a run.
			case ("deleterun") :
				deleteRun();
				break;

				// User wants to see the preferences page.
			case ("preferences") :
				$page = makePreferences();
				break;

				// A banker wants to see the transaction log for a user.
			case ("showTransactions") :
				$page = showTransactions();
				break;

				// User wants to manage his cans.
			case ("cans") :
				$page = makeCanPage();
				break;

				// Print out fancy global statistics
			case ("globstats") :
				$page = globalStatistics();
				break;

				// User wants to re-validate his email.
			case ("revalidate") :
				validate();
				break;

				// User wants to pop a can.
			case ("popcan") :
				$page = popCan();
				break;

				// Kick a user.
			case ("kickban") :
				$page = kick();
				break;

				// User wants to toggle the empty/full setting of a can.
			case ("togglecan") :
				$page = toggleCan();
				break;

				// close a run.
			case ("endrun") :
				endrun();
				break;

				// Show ore values
			case ("showorevalue") :
				$page = showOreValue();
				break;

				// Show ship values
			case ("showshipvalue") :
				$page = showShipValue();
				break;
				
				// Show Corp Hierarchy
			case ("hier") :
				$page = showHierarchy();
				break;

				// manage payouts
			case ("payout") :
				$page = payout();
				break;

				// set/view the online time
			case ("onlinetime") :
				$page = onlineTime();
				break;

				// Mods a template
			case ("edittemplate") :
				$page = editTemplate();
				break;

				// Some Admin wants to change the ore values.
			case ("changeow") :
				$page = makeOreWorth();
				break;

				// Some Admin wants to change the ore values.
			case ("changesv") :
				$page = makeShipValue();
				break;
				
				// Password change request. We wont touch that.
			case ("changepw") :
				$page = makePWChangeForm();
				break;

				// User wants to join the selected run.
			case ("joinrun") :
				$page = joinRun();
				break;

				// User wants to part the selected run.
			case ("partrun") :
				$page = leaveRun();
				break;

				// Password change request. We wont touch that.
			case ("lostpass") :
				$page = makeLostPassForm();
				break;
				
				// Lotto: Create group
			case ("lotto_createGroup") :
				$page = lotto_createGroup();
				break;

				// add ore from a haul to an open run.
			case ("addhaul") :
				$page = addHaulPage();
				break;

				// Edit site configuration
			case ("configuration") :
				$page = configuration();
				break;

				// Add an event.
			case ("addevent") :
				$page = addEvent();
				break;

				// Show all events.
			case ("showevents") :
				$page = showEvents();
				break;

				// Join an Event
			case ("joinevent") :
				$page = joinEvent();
				break;

				// Show an event.
			case ("showevent") :
				$page = showEvent();
				break;

				// lists all ore runs.
			case ("list") :
				$page = listRuns();
				$ajax = 60;
				break;

				// Manage wallet
			case ("manageWallet") :
				$page = manageWallet();
				break;

				// Show current ranks
			case ("showranks") :
				$page = showRanks();
				break;

				// delete a rank
			case ("deleterank") :
				$page = delRank();
				break;

				// delete an event from the database.
			case ("deleteevent") :
				$page = deleteEvent();
				break;

				// lists all users.
			case ("editusers") :
				$page = listUsers();
				break;

				// lists one user.
			case ("edituser") :
				$page = listUser();
				break;

				// prints the form for a new run.
			case ("newrun") :
				$page = makeNewOreRunPage();
				break;

				// add a new user.
			case ("newuser") :
				$page = makeAddUserForm();
				break;

				// Toggle the charity flag.
			case ("toggleCharity") :
				toggleCharity();
				break;

				/* Locking unlocking */
			case ("lockrun") :
				toggleLock();
				break;

				// prints the main welcome page.
			default :
				$page = makeWelcome();
				break;
				
				/* LOTTO STUFF */
			case ("editLotto") :
				$page = lotto_editLottery();
				break;

			case ("lotto") :
				$page = lotto_playLotto();
				break;

			case ("claimTicket") :
				lotto_claimTicket();
				break;

			case ("drawLotto") :
				lotto_draw();
				break;

			case ("buycredits") :
				$page = lotto_buyTickets();
				break;
				
			case ("style") :
				$page = style();
				break;
			case ("getItemList") :
				$page = getItemList();
				break;
			case ("switch") :
				$MySelf = null;
				$_SESSION['MySelf'] = null;
				unset($_SERVER[QUERY_STRING]);
				makeLoginPage($SUPPLIED_USERNAME);
				break;
		}
	
	if($ajax > 1){
		$ajaxHtml = "<script>window.setTimeout(function(){\$.ajax({";
		if(isset($_REQUEST['ajax'])){
			$ajaxHtml .= "url: '?". $_SERVER['QUERY_STRING'] ."',";
		}else{
			$ajaxHtml .= "url: '?". $_SERVER['QUERY_STRING'] ."&ajax',";
		}
		$ajaxHtml .= "success: function(data) {\$('#content').html(data);}";
		$ajaxHtml .= "});},(" . ($ajax * 1000) . "));</script>";
		
		$page .= $ajaxHtml;
	}
		
	if(isset($_REQUEST['ajax'])){
	
		$html = new html;
		$page = $html->clean($page);
		
		print($page);
	}else{
		// Clean & Print the page.
		$html = new html;
		$html->addBody($page);
		print ($html->flush());
	}
}
?>

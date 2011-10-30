<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/process/post.php,v 1.26 2008/05/01 15:37:24 mining Exp $
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
* function process_post()
* is the complement to its sister function process_get().  Same applies here,
* except there is no default case, which would mean that the user submited an
* empty form.
*/
function process_post() {

	/* We switch according to the $_POST[action] variable, which is a hidden
	* submit formfield in each <form>. see html/add*.txt for more information.
	*/
	switch ("$_POST[action]") {

		/*
		* Add new user. We wont touch that here. Let auth() handle that.
		*/
		case ("newuser") :
			addNewUser();
			break;

			/*
			 * Update to the about box in profiles.
			 */
		case ("modprofile") :
			modProfile();
			break;

			/*
			* Change password. We wont touch that here. Let auth() handle that.
			*/
		case ("changepw") :
			changePassword();
			break;

			/*
			* Change email.
			*/
		case ("changeemail") :
			changeEmail();
			break;

			/*
			* Change can view preferences.
			*/
		case ("changecanpage") :
			changeCanPrefs();
			break;

			/*
				* Update API Key
				*/
		case ("update_api") :
			global $MySelf;
			$api = new api($MySelf->getID());
			if ($_POST[deleteKey]) {
				// Delete api Key
				$api->deleteApiKey();
				makeNotice("Your API key has been delete from the database.", "notice", "API Key wipe success", "index.php?action=preferences");
			} else {
				// Update api key
				$api->setApiKey($_POST[apiID], $_POST[apiKey]);
				makeNotice("Your new API key has been stored.", "notice", "API Key update success", "index.php?action=preferences");
			}
			break;

			/*
			* Add a Rank
			*/
		case ("addnewrank") :
			addRank();
			break;

			/*
			* Edit the ranks
			*/
		case ("editranks") :
			editRanks();
			break;

			/*
			* Change opt-in status.
			*/
		case ("optIn") :
			toggleOptIn();
			break;

			/*
			* Change See Inoffical Runs Setting (sir)
			*/
		case ("sirchange") :
			sirchange();
			break;

			/*
			* Submiting a template change form
			*/
		case ("editTemplate") :
			editTemplate();
			break;

			/*
				* Change ore value.
				*/
		case ("changeore") :
			changeOreValue();
			break;

			/*
				* Change ship value.
				*/
		case ("changeship") :
			changeShipValue();
			break;

			
			/*
			* Delete pending payout request
			*/
		case ("deleteRequest") :
			deletePayoutRequest();
			break;

			/*
			* Modify online time.
			*/
		case ("modonlinetime") :
			modOnlineTime();
			break;

			/*
			* Modify site settings.
			*/
		case ("configuration") :
			modConfiguration();
			break;

			/*
			* Add an event to the DB
			*/
		case ("addevent") :
			addEventToDB();
			break;

			/*
			* Request payout.
			*/
		case ("requestPayout") :
			requestPayout();
			break;

			/*
			* Transfer Money
			*/
		case ("transferMoney") :
			transferMoney();
			break;

			/*
			* Do the payouts
			*/
		case ("payout") :
			doPayout();
			break;

			/*
			* Create a new can in the Database.
			*/
		case ("addcan") :
			addCanToDatabase();
			break;

			/*
			 * Admin request to change a user.
			 */
		case ("edituser") :
			editUser();
			break;

			/*
			* AddRun
			* This adds a new run to the database.
			*/
		case ("addrun") :
			addRun();
			break;

			/*
			 * Analog to AddRun, just for Hauls.
			 */
		case ("addhaul") :
			addHaul();
			break;

			/*
			 * Create a new transaction.
			 */
		case ("transaction") :
			createTransaction();
			break;
			
						/*
			 * Lotto stuff
			 */
		case ("editLottoTickets") :
			lotto_editCreditsInDB();
			break;

		case ("createDrawing") :
			lotto_createDrawing();
			break;

		case ("lottoBuyCredits") :
			lotto_buyTickets();
			break;
	}
}
?>
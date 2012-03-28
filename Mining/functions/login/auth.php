<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/login/auth.php,v 1.4 2008/10/22 12:15:15 mining Exp $
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
* Auth()
* This is called at the very moment someone calls the page, and
* then on *every* page thereafter. This may consume some CPU power,
* but it prevents cross-site code injection and other ebil things.
*/

function auth() {
	// Globals
	global $DB;
	global $TIMEMARK;
	global $IGB;
	
	// Handle possible logouts, activations et all.
	include_once ('./functions/login/preAuth.php');
	
	// Trust, INC.
	$alert = getConfig("trustSetting");
	if ($IGB && ($alert == 2)) {

		// So we are an IGB call and we want passwordless logins.

		// Check for a previous "Login"
		$MySelf = authKeyIsValid();

		// Now we check if MySelf is "true" if it is, we have a valid login.
		if ($MySelf == false) {

			/*
			 * Okay here we want passwordless logins. We also have no previous active login.
			 * This means we now have to search the database for a matching username.
			 */

			global $EVE_Charname;
			$MySelf = authVerify(sanitize($EVE_Charname), false, true);

			/*
			 * If we were successfull $MySelf does now contain a userrecord, or is false on failure.
			 */

			if ($MySelf == false) {

				/*
				 * No such user found. To avoid a login loop we will now break the cycle and
				 * present the user with the request account form.
				 */
				 
				makeNotice("You do not belong here. Leave at once!", "warning", "ACCESS DENIED");
				die();
				global $page;
				$page = makeRequestAccountPage(true) . makeFooter();
				print ($page);
			} else {
				/*
				 * Here we found a matching user. What we do now is to create an auth key
				 * for this user, drop other logins from the database and store the login time.
				 */

				createAuthKey($MySelf);
				$DB->query("update users set lastlogin = '$TIMEMARK' where username = '" . strtolower(sanitize($EVE_Charname)) . "'");
				$_SESSION['MySelf'] = base64_encode(serialize($MySelf));

				// Beta Warning.
				global $IS_BETA;
				if ($IS_BETA && ($_SESSION[betawarning] != $MySelf->getLastlogin())) {
					$_SESSION[betawarning] = $MySelf->getLastlogin();
					makeNotice("You are using a beta version of MiningBuddy. Be aware that some functions may not " .
					"be ready for production servers, and that there may be bugs around. You have been warned.", "warning", "Beta Warning");
				}
			}
		}

	} else {

		/*
		* Lets see wether there is a login request, this has priority over
		* anything else. We dont want to create a login loop.
		*/
		if (isset ($_POST[login])) {
			/*
			* So we have a login post. We will now check the username and
			* password combination against the database. Lets see if it is
			* a legit user or a fraud^wtypo.
			*/

			// The dynamical banning module.
			
			checkBan();
			
			$SUPPLIED_USERNAME = strtolower(sanitize($_POST[username]));
			
			// Check for validity.
			if (!ctypeAlnum($SUPPLIED_USERNAME)) {
				makeNotice("Invalid username. Only characters a-z, A-Z and 0-9 are allowed.", "error", "Invalid Username");
			}
			
			if(!isset($_SESSION[testauth])){
				$SUPPLIED_PASSWORD = sha1($_POST[password]);

				// Lets check the password.
				$MySelf = authVerify($SUPPLIED_USERNAME, $SUPPLIED_PASSWORD);
			}else{
				$MySelf = authVerify($SUPPLIED_USERNAME, false);
			}
			
			if ($MySelf == false) {

				// Lets try again, shall we?
				makeLoginPage($SUPPLIED_USERNAME);

			} else {
				if ($MySelf->isValid()) {
		
					// storing the new login time.
					$DB->query("update users set lastlogin = '$TIMEMARK' where username = '" . $MySelf->getUsername() . "'");

					// Create the auth-key.
					createAuthKey($MySelf);

				}
			}
			// We are done here.
			$_SESSION['MySelf'] = base64_encode(serialize($MySelf));

			// Beta Warning.
			global $IS_BETA;
			if ($IS_BETA && ($_SESSION[betawarning] != $MySelf->getLastlogin())) {
				$_SESSION[betawarning] = $MySelf->getLastlogin();
				makeNotice("You are using a beta version of MiningBuddy. Be aware that some functions may not " .
				"be ready for production servers, and that there may be bugs around. You have been warned.", "warning", "Beta Warning");
			} else {
				header("Location: index.php?$_SERVER[QUERY_STRING]");
				die();
			}
		}

		/*
		 * This is to check wether the user still has a valid login ticket.
		 */
		$MySelf = authKeyIsValid();

		if ($MySelf == false) {
			$_SESSION[lastModDisplay] = false;
			session_destroy();
			makeLoginPage();
			die();
		}
	}

	/*
	 * Print motd. (Only on login) - and only if set.
	 */
	$MOTD = getTemplate("motd", "announce");
	if (!$_SESSION[seenMotd] && !empty ($MOTD)) {
		$_SESSION[seenMotd] = true;
		makeNotice(nl2br(stripslashes($MOTD)), "notice", "Announcement");
	}

	return ($MySelf);

}
?>
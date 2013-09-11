<?php

/*
* MiningBuddy (http://miningbuddy.net)
* $Header: /usr/home/mining/cvs/mining/functions/system/init.php,v 1.4 2008/01/03 15:49:08 mining Exp $
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

/* We use our own error handling. */
require_once ('./functions/system/errorHandler.php');

/* Include all the functions */
require_once ('./functions/registry.php');

/* get the domain name. */
$DOMAIN = $_SERVER['HTTP_HOST'];
$SCRIPT = dirname($_SERVER['SCRIPT_NAME']);
$URL = "http://" . $DOMAIN . $SCRIPT;

/* Initialize cookies. Mhh, cookies. */
$currentCookieParams = session_get_cookie_params();

session_set_cookie_params(
   	$currentCookieParams["lifetime"],
   	$SCRIPT,
   	$DOMAIN,
   	$currentCookieParams["secure"],
   	$currentCookieParams["httponly"]
);

session_name('MBPSession');
session_start();

/* Die and roll over if we use an archaic PHP version. */
if (version_compare(phpversion(), "5.0.0") == "-1") {
	die("You are running PHP Version " . phpversion() . ", but at least PHP 5.0.0 is required.<br>" .
	"Please upgrade your PHP version and try again.<br>");
}

/* Check that Register globals is OFF */
if (ini_get('register_globals')) {
	die("<b>Error:</b> You have register_globals set to on in your php.ini.<br><br> " .
	"Not only is this extremly insecure, but even more problematic: " .
	"MiningBuddy does not work on hosts with register_globals on. " .
	"Turn it off, and try again.");
}

/* Set the error handler. */
//set_error_handler('errorHandler', E_WARNING);

/* Do we have the config file? */
if (!isset($_SESSION["initdone"]) || $_SESSION["initdone"] != true) {
	if (!file_exists("./etc/config." . $DOMAIN . ".php")) {
		die("Please set up MiningBuddy first by copying /etc/config-release.php " . "to /etc/config." . $DOMAIN . ".php and edit it to suit your needs.");
	}
}

/* Include important files. */
require_once ("./etc/config." . $DOMAIN . ".php");

/* is the images cache dir existant and writeable? */
if (!file_exists("./images/cache/" . $DOMAIN) && is_writable("./images/cache/" . $DOMAIN)) {
	mkdir("./images/cache/" . $DOMAIN, 0755);
}

/* load Pear. */
require_once ('DB.php');
if (!class_exists('DB')) {
	die("<b>Error:</b> Unable to load PEAR-DB! It is a requirement. Please add this package, and try again.");
}

/* Config file compatible with this release? */
if (!isset($_SESSION["initdone"]) || $_SESSION['initdone'] != true) {
	if ("$CONF_VER" != "$CONFIGVER") {
		die("Your etc/config." . $DOMAIN . ".php file is out of date. Please update it.");
	}
	elseif ($HAVE_READ != true) {
		die("Please edit the configuration file ./etc/config." . $DOMAIN . ".php!");
	}
}

/* Create the database - needed before auth! */
$DB = makeDB();

/* Create empty user object */
$MySelf = new user(false, false);

/* Lets check if we have the right SQL version */
if (!isset($_SESSION["initdone"]) || $_SESSION["initdone"] != true) {

	global $SQLVER;

	// Check the Version information of the Database.
	$CURRENT = $DB->getCol("SELECT value FROM config WHERE name = 'version' LIMIT 1");

	// NO schema found!
	if ($DB->isError($CURRENT)) {
		print ("<br><center><body bgcolor=\"#2E2E2E\"><font color= white><body link=\"#00FF00\" vlink=\"##00FF00\" alink=\"#FF0000\">");
		print ("<br><br><br><br><br><br><br><br><br><br><br><br>");
		die("<CENTER><H1>Mining Buddy Tables need to be Created<BR><a href=\"./buildDatabase.php\">Click to Continue</H1></a></CENTER>");
	}
	// Version number incorrect.
	if ("$CURRENT[0]" < "$SQLVER") {

		// Install upgrade.
		$filename = "./doc/sql/mysql-update-" . $CURRENT[0] . "-" . ($CURRENT[0] + 1) . ".php";

		// Update the tables.
		print ("<br><center><body bgcolor=\"#2E2E2E\"><font color= white><body link=\"#00FF00\" vlink=\"##00FF00\" alink=\"#FF0000\">");
		print ("<br><br><br><br><br><br><br><br><br><br><br><br>");
		print ("<H1>Your SQL tables are not compatible.</H1><br>");

		if (file_exists($filename)) {
			die("Please update your sql tables <a href=\"$filename\">click here</a>.");
		} else {
			die("Database update file may be missing.  Check files.");
		}
	}
}

/* Create a timestamp - needed before auth! */
$TIMEMARK = date('U') - (getConfig("timeOffset") * 60 * 60);

/* Is this call made from within EvE? */
if (ereg("EVE-IGB", $_SERVER['HTTP_USER_AGENT'])) {

	$IGB = TRUE;

	// Trust, Inc.
	if (getConfig("trustSetting") > 0) {
		if ($_SERVER['HTTP_EVE_TRUSTED'] != 'Yes') {

			// Request Trust
			echo '<META HTTP-EQUIV="refresh" CONTENT="2;URL=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">';
			echo "<body onLoad=\"CCPEVE.requestTrust('http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ."');\" bgcolor=\"#000000\" text=\"#FFFFFF\"> ";
			ob_flush();
			die();

		} else {
			$EVE_Charname = $_SERVER['HTTP_EVE_CHARNAME'];
		}
	}
}

/* If we are this far, we have passed the checks. */
if (!isset($_SESSION["initdone"]) || $_SESSION["initdone"] != true) {
	$_SESSION['initdone'] = true;
}

// Load the sitename.
$SITENAME = getConfig("sitename");

global $BLESSED;
if ($BLESSED) {
	$VERSION .= " [blessed]";
}

//  Ebil MSIE!
//if (!$_SESSION["initdone"] && ereg("MSIE 7", $_SERVER[HTTP_USER_AGENT]) && !isset ($_GET[image])) {
//	makeNotice("MiningBuddy does not work with Internet Explorer 7.0 and above. It does work, however with free alternatives like FireFox, Mozilla or Seamonkey - and of course, from in-game. " .
//	"<br>- Mozilla: <a href=\"http:/mozilla.org\">mozilla.org</a>" . "<br>- Firefox: <a href=\"http:/mozilla.com\">mozilla.com</a>" .
//	"<br>- Seamonkey: <a href=\"http://www.mozilla.org/projects/seamonkey/\">http://www.mozilla.org/projects/seamonkey/</a>", "error", "Browser not supported", " ", " ");
//}

// Update Check!
$UPDATE = checkForUpdate();
?>

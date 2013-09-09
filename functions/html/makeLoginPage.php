<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/html/makeLoginPage.php,v 1.25 2008/10/22 12:15:15 mining Exp $
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

// prints a neat login page.
function makeLoginPage($user = false) {

	// We need global Variables.
	global $VERSION;
	global $SITENAME;
	global $IGB;
	global $IS_DEMO;
	global $IS_BETA;
	global $DB;
	global $IGB_VISUAL;

	if ($IGB && $IGB_VISUAL) {
		$login = new table(3, true);
	} else {
		$login = new table(3, true, "id=\"loginTable\"", "align=\"center\"");
	}
	
	$peeps = $DB->getCol("SELECT COUNT(id) FROM users");
	if ($peeps[0] == 0){
		header("Location: index.php?auth=requestaccount&admin=true");
	}

	$login->addHeader(">> Welcome to $VERSION.");
	$login->addRow("#060622");
	$login->addCol($SITENAME, array (
		"colspan" => 3,
		"align" => "center",
		"bold" => true
	));

	if ($user) {
		if ($user == "__invalidchar") {
			$login->addRow("redish");
			$login->addCol("Only characters a-z, A-Z and 0-9 are allowed. " .
			array (
				"bold" => "true",
				"colspan" => 3
			));
		} else if (isset($_SESSION['testauth'])) {
			$login->addRow();
			$login->addCol("Please select the character you wish to login as.", array("colspan"=>3));
		} else {
			$login->addRow("redish");
			$login->addCol("Your supplied credentials are invalid, please check and try again. " .
			"If you cannot remember your password use the Password Recovery link below.", array (
				"bold" => "true",
				"colspan" => 3
			));
		}
	}

	// Show login info for demo.
	if ($IS_DEMO) {
		$login->addRow("#006600");
		$login->addCol("This installation of MiningBuddy runs in demo mode. Login with username demo, password demo. If you get kicked out, someone else logged in with the same account.", array (
			"colspan" => 3,
			"align" => "center",
			"bold" => true
		));
	}

	global $BLESSED;
	if ($BLESSED == true) {
		$login->addRow("#330000");
		$login->addCol("Using a superior hosted slot.", array (
			"colspan" => 3,
			"align" => "center",
			"bold" => true
		));
	}

	// Beta Warning
	if ($IS_BETA) {
		$login->addRow("#904000");
		$login->addCol("-beta version-", array (
			"colspan" => 3,
			"align" => "center",
			"bold" => true
		));
	}

	

	global $AUTH_TYPE;

	if ($AUTH_TYPE != "testauth" || !isset($_SESSION["testauth"])){
		$login->addRow();
		$login->addCol("Username:");
		// Trust, INC.
		global $EVE_Charname;
		if ($EVE_Charname) {
			$login->addCol("<input type=\"text\" name=\"username\" value=\"$EVE_Charname\" maxlength=\"30\">");
		} else {
			$login->addCol("<input type=\"text\" name=\"username\" value=\"" . stripcslashes($user) . "\" maxlength=\"30\">");
		}

		$login->addCol("<img src=\"./images/keys.png\">", array (
			"rowspan" => "2"
		));

		$login->addRow();
		$login->addCol("Password:");
		$login->addCol("<input type=\"password\" name=\"password\" maxlength=\"80\">", array (
			"colspan" => "2"
		));
		$login->addRow("#060622");
		$login->addCol("Please login with your credentials. If you are in need of an account, request an account below and ask your CEO to activate it for you.", array (
			"colspan" => "3",
			"align" => "center"
		));
	}else if($AUTH_TYPE == "testauth" && isset($_SESSION["testauth"])){ // User has logged in, but we need a character name.
		$login->addRow();
		$login->addCol("Character:");
		if($AUTH_TYPE=="testauth"){
			global $TEST_AUTH;
			$eveApiProxyUrl = "https://auth.pleaseignore.com/api/1.0/eveapi/?apikey=$TEST_AUTH&userid=" . $_SESSION[testauth][id];
			$return = file_get_contents($eveApiProxyUrl);
			$obj = json_decode($return, TRUE);
			
			$count = 0;
			
			$select = "<select name=\"username\" >";
			$array = array();
			foreach($obj[keys] as $key){
				$eveApiProxyUrl = "https://auth.pleaseignore.com/api/1.0/eveapi/account/Characters.xml.aspx?apikey=$TEST_AUTH&userid=" . $key[api_user_id];
				$return = file_get_contents($eveApiProxyUrl);
				try{
					$chars = new SimpleXMLElement($return);
				}catch(Exception $ex){
					continue;
				}
				global $TEST_ALLOWED_CORP;
				
				foreach($chars->result[0]->rowset[0] as $row){
					$character = (string)$row[name];
					if($row['corporationName'] != $TEST_ALLOWED_CORP || in_array($character,$array))
						continue;
					if($character == $user)
						$selected = "selected";
					$select .= "<option $selected value='$character'>$character</option>";
					$array[] = $character;
					$count++;
				}
			}
			//var_dump($array);
			$select .= "</select>";
			$login->addCol($select, array("colspan"=>"2"));
			
			if($count == 0){
				session_destroy();
				makenotice("You do not belong here. Leave at once!", "warning", "ACCESS DENIED");
				die();
			}
		}
		//file_put_contents($_SESSION[testauth][id].".xml",print_r($list,true));
	}
	
	if ($IGB && $IGB_VISUAL) {
		$login->addHeaderCentered("<input type=\"submit\" name=\"login\" value=\"login\">");
	} else {
		$login->addHeaderCentered("<input type=\"image\" name=\"login\" value=\"login\" src=\"./images/login.png\">");
	}

	$login->addRow("#060622");
	$login->addCol("<a href=\"index.php?auth=lostpass\">lost password</a>");
	
	$login->addCol("<a href=\"index.php?auth=requestaccount\">request account</a>", array (
		"align" => "right",
		"colspan" => "2"
	));
	
//	$login->addCol("",array("colspan"=>"2"));
	$page = "<br><br><br>";
	if(!isset($_SERVER['QUERY_STRING']) || strstr($_SERVER['QUERY_STRING'],"switch")){
		$page .= "<form action=\"index.php?\" method=\"post\">";
	} else {
		$page .= "<form action=\"index.php?" . $_SERVER['QUERY_STRING'] . "\" method=\"post\">";
	}
	// Add special hidden forms for stupid browsers.
	$browserinfo = new BrowserInfo();
	
	if ( ereg("MSIE", $_SERVER['HTTP_USER_AGENT']) or ( $browserinfo->getBrowser() == BrowserInfo::BROWSER_FIREFOX && $browserinfo->getVersion() >= 4 ) 
//	ereg("Firefox/7", $_SERVER[HTTP_USER_AGENT]) or 
//	ereg("Firefox/6", $_SERVER[HTTP_USER_AGENT]) or 
//	ereg("Firefox/5", $_SERVER[HTTP_USER_AGENT]) or 
//	ereg("Firefox/4", $_SERVER[HTTP_USER_AGENT])
	) {
		$page .= "<input type=\"hidden\" name=\"login\" value=\"login\">";
	}
	
	$page .= $login->flush();
	//$page .= "<input type='hidden' name='redirect' value='$_SERVER[QUERY_STRING]'";
	$page .= "</form><br><br><br>";

	$html = new html;
	$html->addBody($page);
	die($html->flush());
}
?>

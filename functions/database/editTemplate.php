<?php

/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/database/editTemplate.php,v 1.3 2008/01/02 20:01:32 mining Exp $
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
  * Prints a form to edit the selected template, and/or stores it.
  */
 
 function editTemplate () {

 	global $DB;
 	global $MySelf;
 	
 	// Are we allowed to?
 	if (!$MySelf->isAdmin()) {
 		makeNotice("Only an Administator can edit the sites templates.", "warning", "Access denied");
 	}
 	
 	// No Identifier, no service
 	if ($_POST[check]) {
 		// We got the returning form, edit it.
 		numericCheck($_POST[id],0);
 		$ID = $_POST[id];
 		
 		// Fetch the current template, see that its there.
 		$test = $DB->query("SELECT identifier FROM templates WHERE id='$ID' LIMIT 1");
 		
 		if ($test->numRows() ==1) {
 			// We got the template
 			$template = sanitize($_POST[template]);
 			$DB->query("UPDATE templates SET template='".$template."' WHERE id='$ID' LIMIT 1");
 			
 			// Check for success
 			if ($DB->affectedRows() == 1) {
 				// Success!
 				header("Location: index.php?action=edittemplate&id=$ID");
 			} else {
 				// Fail!
 				makeNotice("There was a problem updating the template in the database!", "error", "Internal Error", "index.php?action=edittemplate&id=$ID", "Cancel");
 			}
 		} else {
 			// There is no such template
 			makeNotice("There is no such template in the database!", "error", "Invalid Template!", "index.php?action=edittemplate&id=$ID", "Cancel");
 		}
 		
 		
 	} elseif (empty($_GET[id])) {
 		// No returning form, no identifier.
 		header("Location: index.php?action=configuration");
 	} else {
 		$ID = $_GET[id];
 	}
 	
 	// numericheck!
 	numericCheck($ID,0);
 	
 	$temp = $DB->getCol("SELECT template FROM templates WHERE id='$ID' LIMIT 1");
 	
 	$table = new table(1, true);
 	$table->addHeader(">> Edit template");
 	$table->addRow();
 	$table->addCol("<center><textarea name=\"template\" rows=\"30\" cols=\"60\">".$temp[0]."</textarea></center>");
 	$table->addHeaderCentered("<input type=\"submit\" name=\"submit\" value=\"Edit Template\">");
 	
 	$form1 = "<form action=\"index.php\" method=\"POST\">";
 	$form2 = "<input type=\"hidden\" name=\"check\" value=\"true\">";
 	$form2 .= "<input type=\"hidden\" name=\"action\" value=\"editTemplate\">";
 	$form2 .= "<input type=\"hidden\" name=\"id\" value=\"".$ID."\">";
 	$form2 .= "</form>";
 	
 	$backlink = "<br><a href=\"index.php?action=configuration\">Back to configuration</a>";
 	
 	return ("<h2>Edit the template</h2>" . $form1 . $table->flush() . $form2 . $backlink);
 	 	
 }
 
 ?>

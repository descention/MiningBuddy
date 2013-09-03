<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/email_class.php,v 1.4 2008/01/02 20:01:32 mining Exp $
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

class email {

	// Internal Variables needed 
	private $divider_top;
	private $divider_bottom;
	private $template;
	private $email;

	// Constructor
	public function __construct($template = false) {
		// Create email headers and footers
		$this->divider_top = $this->createDividerTop();
		$this->divider_bottom = $this->createDividerBottom();

		// Load a template, if requested.
		if ($template) {
			$this->loadTemplate($template);
		}
	}

	// Public: Load a template into the email.
	public function loadTemplate($template) {

		// Load template from database.
		$this->template = getTemplate("$template", "email");

		// Vars needed for replacement.
		global $MySelf;
		global $URL;

		// replace common vars.
		$this->template = str_replace("{{SITENAME}}", getConfig("sitename"), $this->template);
		$this->template = str_replace("{{USERNAME}}", ucfirst($MySelf->getUsername()), $this->template);
		$this->template = str_replace("{{URL}}", $URL, $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
		$this->template = str_replace("{{}}", "", $this->template);
	}

	// Public: Load current template from this email.
	public function getTemplate() {
		return ($this->template);
	}

	// Public: Store current template into this email.
	public function setTemplate($template) {
		$this->template = $template;
	}

	// Public: Send email or return it
	public function create($type) {
		$this->email = $this->divider_top . "\n" . $this->template . "\n" . $this->divider_bottom;
		return ("<pre>" . $this->email . "</pre>");
	}

	// Internal: Create Top Divider
	private function createDividerTop() {
		$sitename = getConfig("sitename");
		$ver = str_pad("=[ $sitename ]=", "70", "=", STR_PAD_RIGHT);
		return ($ver);
	}

	// Internal: Create Bottom Divider	
	private function createDividerBottom() {
		global $VERSION;
		$ver = str_pad("=[ $VERSION ]=", "70", "=", STR_PAD_LEFT);
		return ($ver);
	}

}
?>
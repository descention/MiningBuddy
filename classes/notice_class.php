<?PHP


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/notice_class.php,v 1.6 2008/01/02 20:01:32 mining Exp $
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

class notice {

	// Lets declare the c
	private $body;
	private $title;
	private $backlink;
	private $backlink_text;
	private $type;
	private $table;

	public function __construct() {
		global $VERSION;
		$this->title = "$VERSION - Notice";
		$this->backlink = "index.php";
		$this->backlink_test = "OK";
		$this->type = 0;
		$this->table = new table(1);
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setBody($body) {
		$this->body = $body;
	}

	public function setBacklink($url) {
		$this->backlink = $url;
	}

	public function setBackLinkText($backlink_text) {
		$this->backlink_text = "$backlink_text";
	}

	public function setType($type) {

		if (!is_numeric($type)) {
			die("Internal error: Type of notice not an integer.");
		}

		if ($type < 0 || $type > 2) {
			die("Internal error: Type invalid (less than zero, greater two!)");
		}

		$this->type = $type;
	}

	public function render() {
		$this->table->addRow("#444455", "center");
		$this->table->addCol($this->title);
		$this->table->addRow("#333344");
		$this->table->addCol($this->body);
		$this->table->addRow("#444455", "center");
		$this->table->addCol("[<a href=\"" . $this->backlink . "\>" . $this->backlink_text . "</a>]");
	}
}
?>
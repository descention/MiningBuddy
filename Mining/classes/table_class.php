<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/classes/table_class.php,v 1.15 2008/01/04 12:32:51 mining Exp $
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

// Note: $page .= "<table border=\"0\" width=\"96%\" cellpadding=\"0\" cellspacing=\"0\">";

/*
 * This is a uniform class to easily create theme-specific tables.
 */

class table {

	// Variable declarations
	private $html; // The container for the html
	private $bgc; // Array with row colors
	private $bgi; // Index used to alternate the bgc array
	private $current_row; // Current row counter
	private $current_col; // Current col counter
	private $columns; // Total number of columns.
	private $rowIsOpen; // bool to mem if a row is open.
	private $alternating; // If we use alteranting row colors.
	private $hasContent; // Set to true when the first row has been completed.
	private $width; // Width.

	// Constructator!!1 :P
	public function __construct($cols, $alt = false, $width = "%%WIDTH%%", $align = false) {
		// The $cols (columns) must be a positive integer greater of equal one.
		if ($cols <= 0) {
			makeNotice("Invalid column count given to constructor", "error", "Internal Error");
		}

		// Default values for new tables.		
		$this->bgc = array (
			"#333344",
			"#444455"
		); // Array of background colors
		$this->bgi = 1; // Index used in conjunction with above array.
		$this->current_col = 0; // Counts the current columns in the current row
		$this->current_row = 0; // Counts the number of rows
		$this->alternating = $alt; // True if we use alternating colors.
		$this->columns = $cols; // Defines the max number of columns.
		$this->html = "<table " . $align . $width . " cellpadding=\"2\" cellspacing=\"0\">"; // Open up the table.
	}

	public function addRow($class = false, $valign = false) {
		// Close current row, if applicable.
		$this->closeRow();
		
		
		
		if (!$class) {
			$class = $this->bgc[$this->bgi];
			$this->bgi = 1 - $this->bgi;
		}

		//$class = str_replace('#','x',$class);
		
		if ($valign) {
			$valign = "valign=\"" . $valign . "\"";
		}

		// Do we want alternating table colors?
		if ($this->alternating) {
			if(strpos($class,"#") === false){
				$this->html .= "<tr class=\"" . $class . "\" $valign>";
			} else {
				$this->html .= "<tr bgcolor=\"" . $class . "\" $valign>";
			}
		} else {
			$this->html .= "<tr>";
		}

		// Reset the column count (new row)
		$this->current_col = 0; // Reset the column counter.
		$this->rowIsOpen = true; // Mark the table as open.
		$this->current_row++; // Increase row count.
	}

	private function closeRow() {
		if ($this->rowIsOpen) { // Do we have an open row?
			if ($this->current_col != $this->columns) { // Is the row filled?
				$BT = nl2br(print_r($this, true) . "<br>" . print_r(debug_backtrace(), true));
				makeNotice("Current row not finished feeding yet!<br><br>" . $BT, "error", "Internal Error");
			} else {
				// Its opened and filled. Close the row, and mark it as closed, too.
				$this->html .= "</tr>"; // Close row.
				$this->rowIsOpen = false; // Mark row as closed.
			}
		}
	}

	public function hasContent() {
		return ($this->hasContent);
	}

	public function addHeader($text) {
		$this->addRow("#222233");
		$cols = $this->columns;
		$this->addCol("$text", array (
			"bold" => true,
			"colspan" => $cols
		));
	}

	public function addHeaderCentered($text) {
		$this->addRow("#222233");
		$cols = $this->columns;
		$this->addCol("$text", array (
			"bold" => true,
			"colspan" => $cols,
			"align" => "center"
		));
	}

	public function addCol($cont, $modes) {
		// Do we have an open row?
		if (!$this->rowIsOpen) {
			makeNotice("Row not opened.", "error", "Internal Error");
		}
		$staticAttributes = "";
		$colspan = 1;
		// Do we have a valid modes array?
		if (isset ($modes) && is_array($modes)) {
			foreach($modes as $key=>$value){
				if($key=="bold"){
					$bold = "<b>";
					$bold_end = "</b>";
				}else{
					$staticAttributes .= "$key=\"$value\" ";
					if($key == "colspan"){
						$colspan = $value;
					}
				}
			}
		} else {
			// Default Values go here (if no array set)
			$staticAttributes .= "colspan='1' rowspan='1' ";
		}

		// Are we over-doing it?
		if ($this->current_col + $colspan > $this->columns) {
			debug($this);
			makeNotice("Too many columns requested.", "error", "Internal Error");
		}

		// Add the content.
		$this->html .= "<td $staticAttributes>" . $bold . $cont . $bold_end . "</td>";
		$this->current_col = $this->current_col + $colspan;
		$this->hasContent = true;
	}

	public function flush() {
		// Close and opened rows, if any.
		$this->closeRow();

		// Finnish up.
		$this->html .= "</table>";
		return ($this->html);
	}

}
?>
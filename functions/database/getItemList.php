<?php
function getItemList(){
	global $DB;
	global $DBORE;
	global $STATIC_DB;
	
	$page = "";
	if(isset($_GET[q]) && $_GET[q] != ""){
		$search = sanitize($_GET[q]);
		$items = $DB->query("select itemName from itemList where itemName like '%$search%';");
		while ($item = $items->fetchRow()) {
			$name = $item['itemName'];
			$dbfriendly = str_replace(" ", "", ucwords($name));
			$dbfriendly = str_replace("-", "", ucwords($dbfriendly));
			$page .= "<a onclick='javascript:addItem(this)' name='$dbfriendly'>$name</a>";
		}
	}
	return $page;
}
?>
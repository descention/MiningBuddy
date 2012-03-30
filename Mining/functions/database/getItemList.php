<?php
function getItemList(){
	global $DB;
	global $DBORE;
	global $STATIC_DB;
	
	$page = "";
	if(isset($_GET[q]) && $_GET[q] != ""){
		$search = sanitize($_GET[q]);
		$items = $DB->query("select typeName from $STATIC_DB.invTypes where typeName like '%$search%';");
		while ($item = $items->fetchRow()) {
			$name = $item[typeName];
			$dbfriendly = str_replace(" ", "", ucwords($name));
			$dbfriendly = str_replace("-", "", ucwords($dbfriendly));
			$page .= "<a onclick='javascript:addItem(this)' name='$dbfriendly'>$name</a>";
		}
	}
	return $page;
}
?>
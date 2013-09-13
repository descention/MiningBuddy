<?php

function style(){
	global $MySelf;
	$username = ucfirst($MySelf->getUsername());
	$file = "./include/html/" . $username . ".css";
	if(isset($_POST[style])){
		$content = $_POST[style];
		file_put_contents($file,$content);
		
	}else{
		$content = file_get_contents($file);
	}
	$page = "";
	$page .= "<form method='post'>";
	$page .= "<textarea name='style' rows=\"30\" cols=\"100\" >$content</textarea><br/>";
	
	$page .= "<input type='submit' value='Submit' />";
	
	$page .= "</form>";
	return $page;
}
?>
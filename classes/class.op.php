<?php
class Op{
	private $ID;
	
	public function __construct($id = 0){
		if($id == 0){
			// need to create a new run
			
		}else{
			// opening an existing run
			$this->ID = $id;
			
		}
	}
	
	public function Create(){
		$DB->query("insert into runs (location, starttime, supervisor, corpkeeps, isOfficial, oreGlue, shipGlue,optype) " . "values (?,?,?,?,?,?,?,?)", array (
			"$location",
			"$starttime",
			"$supervisor",
			$tax,
			$official,
			"$TIMEMARK",
			0,
			"$optype",	
		));
	}
}
?>
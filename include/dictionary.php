<?php

require 'include/database.php';
require 'include/entry.php';

class Dictionary {
	private $database;
	
	function __construct($database){
		$this->database = $database;
	}
	
	function get_entry($entry){
		/* ... */
	}
}

?>

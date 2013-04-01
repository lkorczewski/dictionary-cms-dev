<?php

require_once 'debugger/debugger.php';
require_once 'database/database.php';
require_once __DIR__.'/entry.php';

class Dictionary {
	private $database;  // initialized database object
	
	function __construct($database){
		$this->database = $database;
	}
	
	function get_database(){
		return $this->database;
	}
	
	function get_entry($headword){
		// to do: only the first headword is all are the same
		
		$entry = new Entry($this);
		
		$query = "SELECT * FROM entries WHERE headword = '$headword';"; // needs escaping!
		$entry_result = $this->database->query($query);
		Debugger::dump($entry_result);
		
		$entry->set_id($entry_result[0]['entry_id']);
		$entry->set_headword($entry_result[0]['headword']);
		$entry->pull();
		
		Debugger::dump($entry);
		
		return $entry;
	}
}

?>

<?php

require_once __DIR__.'/dictionary.php';
require_once __DIR__.'/sense.php';

class Entry {
	private $dictionary;
	
	private $id;
	
	private $headword;
	
	private $senses;
	private $sense_iterator;
	
	//------------------------------------------------
	// constructor
	//------------------------------------------------
	
	function __construct(Dictionary $dictionary){
		$this->dictionary = $dictionary;
		$this->database = $dictionary->get_database();
		
		$this->senses = array();
		$this->sense_iterator = 0;
	}
	
	//------------------------------------------------
	// id management
	//------------------------------------------------
	
	function set_id($id){
		$this->id = $id;
	}
	
	//------------------------------------------------
	// headword management
	//------------------------------------------------
	
	function set_headword($headword){
		$this->headword = $headword;
	}
	
	function get_headword(){
		return $this->headword;
	}
	
	//------------------------------------------------
	// sense management
	//------------------------------------------------
	
	function add_sense(){
		$sense = new Sense($this->dictionary);
		$this->senses[] = $sense;
		
		return $sense;
	}
	
	function get_sense(){
		if(!isset($this->senses[$this->sense_iterator])) return false;
		
		$sense = $this->senses[$this->sense_iterator];
		$this->sense_iterator++;
		
		return $sense;
	}
	
	//------------------------------------------------
	// database interface
	//------------------------------------------------
	
	function pull(){
		
		// senses
		
		$query =
			'SELECT *'.
			' FROM senses'.
			" WHERE entry_id = {$this->id} AND parent_sense_id IS NULL".
			' ORDER BY `order`'.
			';';
		$senses_result = $this->database->query($query);
		Debugger::dump($query);
		Debugger::dump($senses_result);
		
		// adding senses (currently only two levels)
		
		foreach($senses_result as $sense_result){
			$sense = $this->add_sense();
			$sense->set_id($sense_result['sense_id']);
			$sense->set_label($sense_result['label']);
			$sense->pull();
		}
	}
}

?>

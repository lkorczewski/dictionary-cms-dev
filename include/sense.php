<?php

require_once __DIR__.'/dictionary.php';
require_once __DIR__.'/translation.php';

class Sense {
	private $dictionary;
	private $databaase;
	
	private $label;
	
	private $translations;
	private $translation_iterator;
	
	private $id;
	private $senses;
	private $sense_iterator;
	
	//------------------------------------------------
	// constructor
	//------------------------------------------------
	
	function __construct(Dictionary $dictionary){
		$this->dictionary = $dictionary;
		$this->database = $dictionary->get_database();
		
		$this->translations = array();
		$this->translation_iterator = 0;
		
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
	// label management
	//------------------------------------------------
	
	function set_label($label){
		$this->label = $label;
	}
	
	function get_label(){
		return $this->label;
	}
	
	//------------------------------------------------
	// translation management
	//------------------------------------------------
	
	function add_translation(){
		$translation = new Translation($this->dictionary);
		$this->translations[] = $translation;
		
		return $translation;
	}
	
	function get_translation(){
		if(!isset($this->translations[$this->translation_iterator])) return false;
		
		$translation = $this->translations[$this->translation_iterator];
		$this->translation_iterator++;
			
		return $translation;
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
		
		// translations
		
		$query = "SELECT * FROM translations WHERE sense_id = {$this->id} ORDER BY `order`;";
		$translations_result = $this->database->query($query);
		Debugger::dump($query);
		Debugger::dump($translations_result);
		
		foreach($translations_result as $translation_result){
			$translation = $this->add_translation();
			$translation->set($translation_result['text']);
		}
		
		// subsenses
		
		$query = "SELECT * FROM senses WHERE parent_sense_id = {$this->id} ORDER BY `order`;";
		$subsenses_result = $this->database->query($query);
		Debugger::dump($query);
		Debugger::dump($subsenses_result);
		
		foreach($subsenses_result as $subsense_result){
			$subsense = $this->add_sense();
			$subsense->set_id($subsense_result['sense_id']);
			$subsense->set_label($subsense_result['label']);
			$subsense->pull();
		}
	}
	
}

?>

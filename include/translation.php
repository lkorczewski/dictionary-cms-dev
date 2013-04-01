<?php

require_once __DIR__.'/dictionary.php';

class Translation {
	private $dictionary;
	
	private $text;
	
	//------------------------------------------------
	// constructor
	//------------------------------------------------
	
	function __construct(Dictionary $dictionary, $text = NULL){
		$this->dictionary = $dictionary;
		
		if($text) $this->text = $text;
	}
	
	//------------------------------------------------
	// setting value
	//------------------------------------------------
	
	function set($text){
		$this->text = $text;
	}
	
	function get(){
		return $this->text;
	}
}

?>

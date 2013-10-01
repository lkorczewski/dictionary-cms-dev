<?php

class Localization {
	
	private $path;
	private $locale;
	private $is_loaded;
	private $texts;
	
	//------------------------------------------------
	// contructor
	//------------------------------------------------
	function __construct($locale = NULL){
		$this->path = '';
		$this->locale = '';
		$this->is_loades = false;
		$this->texts = array();
		
		if($locale) set_locale($locale);
	}
	
	//------------------------------------------------
	// setting path to localization files
	//------------------------------------------------
	function set_path($path){
		$this->path = $path;
	}
	
	//------------------------------------------------
	// setting locale identifier
	//  recommended: pl, en_US, etc.
	//------------------------------------------------
	function set_locale($locale){
		$this->locale = $locale;
	}
	
	//------------------------------------------------
	// loading texts from file
	//------------------------------------------------
	function load_texts(){
		$texts = array();
		include($this->path . '/' . $this->locale . '.php');
		$this->texts = $texts;
	}
	
	//------------------------------------------------
	// getting localized text
	//------------------------------------------------
	function get_text($text){
		if(!$this->is_loaded){
			$this->load_texts();
		}
		
		return isset($this->texts[$text]) && $this->texts[$text] ? $this->texts[$text] : '[[NO TRANSLATION]]';
	}

}

?>

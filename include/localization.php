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
		$this->texts = [];
		
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
		$texts = [];
		include($this->path . '/' . $this->locale . '.php');
		$this->texts = $texts;
	}
	
	//------------------------------------------------
	// getting localized text
	//------------------------------------------------
	function get_text($label){
		if(!$this->is_loaded){
			$this->load_texts();
		}
		
		if(!isset($this->texts[$label]) || !$this->texts[$label]){
			return '[[NO TRANSLATION]]';
		}
		
		return $this->texts[$label];
	}

}

?>

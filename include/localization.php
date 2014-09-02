<?php

namespace DCMS;

class Localization {
	
	private $path       = '';
	private $locale     = '';
	private $is_loaded  = false;
	private $texts      = [];
	
	//------------------------------------------------
	// constructor
	//------------------------------------------------
	function __construct($locale = null){
		if($locale) $this->set_locale($locale);
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
			$this->is_loaded = true;
		}
		
		if(!isset($this->texts[$label]) || !$this->texts[$label]){
			return '[[NO TRANSLATION]]';
		}
		
		return $this->texts[$label];
	}

}


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
	function __construct($path, $locale){
		$this->set_path($path);
		$this->set_locale($locale);
	}
	
	//------------------------------------------------
	// setting path to localization files
	//------------------------------------------------
	protected function set_path($path){
		$this->path = $path;
		
		return $this;
	}
	
	//------------------------------------------------
	// setting locale identifier
	//  recommended values: pl, en_US, etc.
	//------------------------------------------------
	protected function set_locale($locale){
		$this->locale = $locale;
		
		return $this;
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
	// lazy loading texts from file
	//------------------------------------------------
	function lazyload_texts(){
		if(!$this->is_loaded){
			$this->load_texts();
			$this->is_loaded = true;
		}
	}
	
	//------------------------------------------------
	// checking if label is set
	//------------------------------------------------
	function has_text($label){
		if(isset($this->texts[$label]) && $this->texts[$label]){
			return true;
		}
		
		return false;
	}
	
	//------------------------------------------------
	// getting localized text
	//------------------------------------------------
	function get_text($label){
		$this->lazyload_texts();
		
		if(!$this->has_text($label)){
			return '[[NO TRANSLATION]]';
		}
		
		return $this->texts[$label];
	}
	
}


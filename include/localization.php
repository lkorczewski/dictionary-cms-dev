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
	function get_text($label, array $parameters = null){
		$this->lazyload_texts();
		
		if(!$this->has_text($label)){
			return '[[NO TRANSLATION]]';
		}
		
		if($parameters){
			return $this->replace_parameters($this->texts[$label], $parameters);
		}
		
		return $this->texts[$label];
	}
	
	//------------------------------------------------
	// replacing parameters in text
	//------------------------------------------------
	private function replace_parameters($text, $parameters){
		$callback = function($match) use($parameters) {
			return isset($parameters[$match[1]])
				? $parameters[$match[1]]
				: $match[0];
		};
		
		return preg_replace_callback('/{{(\w+)}}/', $callback, $text);
	}
	
}


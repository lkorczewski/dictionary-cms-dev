<?php

namespace DCMS;

use Dictionary\Node_Interface;

use Dictionary\Entry;

abstract class HTML_Layout {
	
	protected $localization;
	
	protected $output  = '';
	
	//--------------------------------------------------------------------
	// constructor
	//--------------------------------------------------------------------
	
	function __construct($localization){
		$this->localization  = $localization;
	}
	
	//--------------------------------------------------------------------
	// public parser
	//--------------------------------------------------------------------
	
	function parse(Entry $entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}
	
	//--------------------------------------------------------------------
	// collection parser
	//--------------------------------------------------------------------
	// ugly
	
	protected function parse_collection(Node_Interface $node, $name){
		
		// todo: better singular/plural handling
		$collection_name  = $name;
		$element_name     = rtrim($name, 's');
		
		$this->output .= '<div class="' . $collection_name . '">' . "\n";
		
		foreach($node->{'get_'.$collection_name}() as $element){
			$this->{'parse_'.$element_name}($element);
		}
		
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// single value parser
	//--------------------------------------------------------------------
	// ugly
	
	protected function parse_single_value(Node_Interface $node, $name){
		
		// todo: better singular/plural handling
		$plural_name    = $name . 's';
		$singular_name  = $name;
		
		$this->output .= '<div class="' . $plural_name . '">' . "\n";
		
		if($value = $node->{'get_'.$singular_name}()){
			$this->{'parse_'.$singular_name}($value);
		}
		
		$this->output .= '</div>' . "\n";
	}
	
}
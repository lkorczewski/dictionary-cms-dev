<?php

namespace Controllers;

use \Phonetics\XSAMPA_Parser;

class Pronunciation extends Abstracts\Multiple_Value {
	
	protected static $name = 'pronunciation';
	
	// todo: doesn't work since moving of value to parameters
	
	function add($id, $value){
		// todo: move to dependency container
		$value = (new XSAMPA_Parser())->parse($value);
		
		parent::add($id, $value);
	}
	
	// todo: doesn't work since moving of value to parameters
	
	function update($id, $value){
		// todo: move to dependency container
		$value = (new XSAMPA_Parser())->parse($value);
		
		parent::update($id, $value);
	}
	
}


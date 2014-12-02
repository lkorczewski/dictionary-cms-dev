<?php

namespace Controllers;

use \Phonetics\XSAMPA_Parser;

class Pronunciation extends Abstracts\Multiple_Value {
	
	protected static $name = 'pronunciation';
	
	function update($id, $value){
		$value = (new XSAMPA_Parser())->parse($value);
		
		parent::update($id, $value);
	}
	
}


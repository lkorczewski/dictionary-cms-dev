<?php

namespace Controllers;

use \Phonetics\XSAMPA_Parser;

class Pronunciation extends Abstracts\Multiple_Value {
	
	protected static $name = 'pronunciation';
	
	function update($id, $text){
		$text = (new XSAMPA_Parser())->parse($text);
		
		parent::update($id, $text);
	}
	
}


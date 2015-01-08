<?php

namespace Controllers;

use \Phonetics\XSAMPA_Parser;

class Pronunciation extends Abstracts\Multiple_Value {
	
	protected static $name = 'pronunciation';
	
//	function add($id, $value){
//		// todo: move to dependency container
//		$value = (new XSAMPA_Parser())->parse($value);
//		
//		parent::add($id, $value);
//	}
	
	function add($node_id){
		$this->init();
		$this->require_authorization();
		
		$value = $this->get_parameter('v');
		if($value){
			$value = $this->services->get('xsampa_parser')->parse($value);
		}
		
		$value_id = $this->value_access->add($node_id, $value);
		$this->handle_query_result($value_id, [
			static::$name . '_id'  => $value_id,
			'value'                => $value,
		]);
	}
	
//	function update($id, $value){
//		// todo: move to dependency container
//		$value = (new XSAMPA_Parser())->parse($value);
//		
//		parent::update($id, $value);
//	}
	
	function update($id){
		$this->init();
		$this->require_authorization();
		
		$value = $this->require_parameter('v');
		$value = $this->services->get('xsampa_parser')->parse($value);
		
		$affected_rows = $this->value_access->update($id, $value);
		$this->handle_update_result($affected_rows, [
			'value'  => $value,
		]);
	}
	
}


<?php

namespace Controllers\Abstracts;

abstract class Single_Value extends JSON_Controller {
	
	protected static $name = 'value';
	
	/** @var \Dictionary\MySQL_Single_Value $value_access */
	protected $value_access;
	
	protected function init(){
		parent::init();
		
		$this->value_access = $this->services->get('data')->access(static::$name);
	}
	
	function add($node_id){
		$this->init();
		$this->require_authorization();
		
		$value = $this->get_parameter('v');
		
		$value_id = $this->value_access->set($node_id, $value);
		$this->handle_query_result($value_id, [
			static::$name . '_id' => $value_id,
		]);
	}
	
	function update($id){
		$this->init();
		$this->require_authorization();
		
		$value = $this->require_parameter('v');
		
		$affected_rows = $this->value_access->update($id, $value);
		$this->handle_update_result($affected_rows, [
			'value' => $value,
		]);
	}
	
	function delete($id){
		$this->init();
		$this->require_authorization();
		
		$affected_rows = $this->value_access->delete($id);
		$this->handle_query_result($affected_rows);
	}
	
}


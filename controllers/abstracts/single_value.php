<?php

namespace Controllers\Abstracts;

abstract class Single_Value extends JSON_Controller {
	
	protected static $name = 'value';
	
	protected $value_access;
	
	function update($id, $value){
		$this->init();
		$affected_rows = $this->value_access->update($id, $value);
		$this->handle_result($affected_rows, ['value' => $value]);
	}
	
	function delete($id){
		$this->init();
		$affected_rows = $this->value_access->delete($id);
		$this->handle_result($affected_rows);
	}
	
	protected function init(){
		parent::init();
		
		$this->value_access = $this->services->get('data')->access(static::$name);
	}
}


<?php

namespace Controllers\Abstracts;

abstract class Multiple_Value extends JSON_Controller {
	
	protected static $name = 'value';
	
	protected $value_access;
	
	function update($id, $value){
		$this->init();
		$affected_rows = $this->value_access->update($id, $value);
		$this->handle_update_result($affected_rows, [
			'value' => $value
		]);
	}
	
	function move_up($id){
		$this->init();
		$affected_rows = $this->value_access->move_up($id);
		$this->handle_update_result($affected_rows);
	}
	
	function move_down($id){
		$this->init();
		$affected_rows = $this->value_access->move_down($id);
		$this->handle_update_result($affected_rows);
	}
	
	function delete($id){
		$this->init();
		$result = $this->value_access->delete($id);
		$this->handle_query_result($result);
	}
	
	protected function init(){
		parent::init();
		
		$this->value_access = $this->services->get('data')->access(static::$name);
	}
}


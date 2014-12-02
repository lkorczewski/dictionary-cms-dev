<?php

namespace Controllers\Abstracts;

abstract class Multiple_Node extends JSON_Controller {
	
	protected $node_access;
	
	protected static $name = 'node';
	
	function move_up($node_id){
		$this->init();
		$affected_rows = $this->node_access->move_up($node_id);
		$this->handle_update_result($affected_rows);
	}
	
	function move_down($node_id){
		$this->init();
		$affected_rows = $this->node_access->move_down($node_id);
		$this->handle_update_result($affected_rows);
	}
	
	function delete($node_id){
		$this->init();
		$result = $this->node_access->delete($node_id);
		$this->handle_query_result($result);
	}
	
	protected function init(){
		parent::init();
		
		$this->node_access = $this->services->get('data')->access(static::$name);
	}
}

<?php

namespace Controllers\Abstracts;

abstract class Multiple_Node extends JSON_Controller {
	
	protected static $name = 'node';
	
	protected $node_access;
	
	protected function init(){
		parent::init();
		
		$this->node_access = $this->services->get('data')->access(static::$name);
	}
	
	function add($node_id){
		$this->init();
		$this->require_authorization();
		
		// todo: value_id -> node_id, node_id -> parent_node_id
		$value_id = $this->node_access->add($node_id);
		$this->handle_query_result($value_id, [
			static::$name . '_id' => $value_id,
		]);
	}
	
	function move_up($node_id){
		$this->init();
		$this->require_authorization();
		
		$affected_rows = $this->node_access->move_up($node_id);
		$this->handle_update_result($affected_rows);
	}
	
	function move_down($node_id){
		$this->init();
		$this->require_authorization();
		
		$affected_rows = $this->node_access->move_down($node_id);
		$this->handle_update_result($affected_rows);
	}
	
	function delete($node_id){
		$this->init();
		$this->require_authorization();
		
		$result = $this->node_access->delete($node_id);
		$this->handle_query_result($result);
	}
	
}

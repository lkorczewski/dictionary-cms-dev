<?php

namespace Controllers;

class Sense extends Abstracts\Multiple_Node {
	
	protected static $name = 'sense';
	
	function add($node_id){
		$this->init();
		$this->require_authorization();
		
		$sense_node_id = $this->node_access->add($node_id);
		$sense_label   = $this->node_access->get_label($sense_node_id);
		$this->handle_query_result($sense_node_id, [
			'node_id' => $sense_node_id,
			'label'   => $sense_label,
		]);
	}
	
	function update($node_id){
		$this->init();
		$this->require_authorization();
		
		$sense_node_id = $this->node_access->add($node_id);
		$sense_label   = $this->node_access->get_label($sense_node_id);
		$this->handle_query_result($sense_node_id, [
			'node_id' => $sense_node_id,
			'label'   => $sense_label,
		]);
	}
}

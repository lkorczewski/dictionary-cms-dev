<?php

namespace Controllers;

class Sense extends Abstracts\Multiple_Node {
	
	protected static $name = 'sense';
	
	function update($node_id){
		$this->init();
		$this->require_authorization();
		
		$sense_access = $this->services->get('data')->access('sense');
		$sense_node_id = $sense_access->add($node_id);
		$sense_label   = $sense_access->get_label($sense_node_id);
		$this->handle_query_result($sense_node_id, [
			'node_id' => $sense_node_id,
			'label'   => $sense_label,
		]);
	}
}

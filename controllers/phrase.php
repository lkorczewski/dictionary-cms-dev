<?php

namespace Controllers;

class Phrase extends Abstracts\Multiple_Node {
	
	protected static $name = 'phrase';
	
	function update($node_id){
		$this->init();
		$this->require_authorization();
		
		$value = $this->require_parameter('v');
		
		$affected_rows = $this->node_access->update($node_id, $value);
		$this->handle_update_result($affected_rows);
	}
	
}

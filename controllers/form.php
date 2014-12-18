<?php

namespace Controllers;

// todo: semantically wrong assignment to "Multiple Value"
class Form extends Abstracts\Multiple_Value {
	
	protected static $name = 'form';
	
	function update($id){
		$this->init();
		$this->require_authorization();
		
		$label  = $this->require_parameter('l');
		$form   = $this->require_parameter('f');
		
		$affected_rows = $this->value_access->update($id, $label, $form);
		$this->handle_update_result($affected_rows, [
			'label' => $label,
			'value' => $form,
		]);
	}
	
}


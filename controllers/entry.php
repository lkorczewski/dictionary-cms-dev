<?php

namespace Controllers;

class Entry extends Abstracts\JSON_Controller {
	
	/** @var \Dictionary\MySQL_Entry $entry_access */
	protected $entry_access;
	
	function find(){
		
		/** @var \Dictionary\Dictionary $dictionary */
		$dictionary = $this->services('dictionary');
		
		$headword = isset($_GET['h']) ? $_GET['h'] : '';
		
		$entries = $headword ? $dictionary->get_entries_by_headword($headword) : [];
		
		return [
			'headword' => $headword,
			'entries'  => $entries,
		];
	}
	
	function add(){
		$this->init();
		$entry_node_id = $this->entry_access->add();
		$this->handle_query_result([
			'entry_id' => $entry_node_id,
		]);
	}
	
	// sprawdzić, czy jest używane
	function delete($node_id){
		$this->init();
		
		$result = $this->entry_access->delete($node_id);
		
		$this->handle_query_result($result);
	}
	
	function init(){
		parent::init();
		
		$this->entry_access = $this->services->get('data')->access('entry'); 
	}
}


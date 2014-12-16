<?php

namespace Controllers;

use DCMS\JSON_Response;
use Dictionary\Table_Layout;

class Entry extends Abstracts\JSON_Controller {
	
	/** @var \Dictionary\MySQL_Entry $entry_access */
	protected $entry_access;
	
	
	function find(){
		
		/** @var \Dictionary\Dictionary $dictionary */
		$dictionary = $this->services->get('dictionary');
		
		$headword = isset($_GET['h']) ? $_GET['h'] : '';
		
		$entries = $headword ? $dictionary->get_entries_by_headword($headword) : [];
		
		return [
			'headword' => $headword,
			'entries'  => $entries,
		];
	}
	
	function add(){
		$this->init();
		$this->require_authorization();
		
		$headword = $this->require_parameter('h');
		
		$entry_node_id = $this->entry_access->add($headword);
		$this->handle_query_result($entry_node_id, [
			'entry_id' => $entry_node_id,
		]);
	}
	
	// check if used! should it be available?
	
	function delete($node_id){
		$this->init();
		$this->require_authorization();
		
		$result = $this->entry_access->delete($node_id);
		
		$this->handle_query_result($result);
	}
	
	// gets entry as a JSON structure
	
	function get($node_id){
		parent::init();
		
		/** @var \Dictionary\Dictionary $dictionary */
		$dictionary = $this->services->get('dictionary');
		$entry = $dictionary->get_entry_by_id($node_id);
		
		if($entry === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		if($entry === null){
			// should it report failure? 
			$this->json_response->fail(JSON_Response::MESSAGE_NOTHING_FOUND);
		}
		
		/** @var \Dictionary\Layout $array_layout */
		$array_layout = $this->services->get('array_layout');
		$array_entry = $array_layout->parse_entry($entry);
		
		$this->json_response->return_array($array_entry);
		
	}
	
	function init(){
		parent::init();
		
		$this->entry_access = $this->services->get('data')->access('entry'); 
	}
}


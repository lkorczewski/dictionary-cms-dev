<?php

namespace Controllers;

class Translation extends Controller {
	
	/** @var \DCMS\Request $request */
	protected $request;
	
	/** @var \DCMS\JSON_Response $json_response */
	protected $json_response;
	
	/** @var \Dictionary\MySQL_Translation $translation_access */
	protected $translation_access;
	
	protected function init(){
		$this->request       = $this->services->get('request'); // is it needed?
		$this->json_response = $this->services->get('json_response');
		
		$this->translation_access = $this->services->get('data')->access('translation');
	}
	
	protected function handle_result($affected_rows){
		if($affected_rows === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		if($affected_rows === 0){
			$this->json_response->fail(JSON_Response::MESSAGE_NOTHING_AFFECTED);
		}
		
		$this->json_response->succeed();
	}
	
	function update($translation_id, $text){
		$this->init();
		$affected_rows = $this->translation_access->update($translation_id, $text);
		$this->handle_result($affected_rows);
	}
	
	function move_up($translation_id){
		$this->init();
		$affected_rows = $this->translation_access->move_up($translation_id);
		$this->handle_result($affected_rows);
	}
	
	function move_down($translation_id){
		$this->init();
		$affected_rows = $this->translation_access->move_down($translation_id);
		$this->handle_result($affected_rows);
	}
	
	function delete($translation_id){
		$this->init();
		$affected_rows = $this->translation_access->delete($translation_id);
		$this->handle_result($affected_rows);
	}
	
}

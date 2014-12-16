<?php

namespace Controllers;

use \DCMS\JSON_Response;

class Editor extends Abstracts\JSON_Controller {
	
	/** @var \DCMS\Session $session */
	protected $session;
	
	function init(){
		parent::init();
		
		$this->session = $this->services->get('session');
	}
	
	function log_in(){
		$this->init();
		
		$login    = $this->require_parameter('l');
		$password = $this->require_parameter('p');
		
		/** @var \DCMS\Data $dcms_data */
		$dcms_data = $this->services->get('dcms_data');
		$editor_result = $dcms_data->get_editor($login, $password);
		
		if($editor_result === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		if(count($editor_result) == 0){
			$this->json_response->fail(JSON_Response::MESSAGE_WRONG_CREDENTIALS);
		}
		
		$this->session->set('editor', $login);
		
		$this->json_response->succeed([
			'editor_name' => $editor_result['login'],
		]);
	}
	
	function log_out(){
		$this->init();
		
		/** @var \DCMS\Session $request */
		$session = $this->services->get('session');
		
		$session->delete('editor');
		
		$this->json_response->succeed();
	}
	
}


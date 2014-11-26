<?php

namespace Controllers;

use \DCMS\Session;
use \DCMS\Request;
use \DCMS\Data as DCMS_Data;
use \DCMS\JSON_Response;

class Editor extends Abstracts\Controller {
	
	/** @var Session $session */
	protected $session;
	
	/** @var JSON_Response $json_response */
	protected $json_response;
	
	protected function init(){
		$this->session       = $this->services->get('session');
		$this->json_response = $this->services->get('json_response');
	}
	
	function log_in(){
		$this->init();
		
		$login    = $this->require_parameter('l');
		$password = $this->require_parameter('p');
		
		/** @var DCMS_Data $dcms_data */
		$dcms_data = $this->services->get('dcms_data');
		$editor_result = $dcms_data->get_editor($login, $password);
		
		if($editor_result === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		if(count($editor_result) == 0){
			$this->json_response->fail(JSON_Response::MESSAGE_WRONG_CREDENTIALS);
		}
		
		$this->session->editor = $login;
		
		$this->json_response->succeed([
			'editor_name' => $editor_result['login'],
		]);
	}
	
	function log_out(){
		$this->init();
		
		/** @var Session $request */
		$session = $this->services->get('session');
		
		$session->delete('editor');
		
		$this->json_response->succeed();
	}
	
	protected function require_parameter($parameter){
		
		/** @var Request $request */
		$request = $this->services->get('request');
		
		$value = $request->get_parameter($parameter);
		
		if($value === null){
			$this->json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
		}
		
		return $value;
	}
}


<?php

namespace Controllers;

use \DCMS\Session;
use \DCMS\Request;
use \DCMS\JSON_Response;

class Editor extends Controller {
	
	protected $session;
	protected $json_response;
	
	protected function init(){
		
		/** @var Session $session */
		$this->session = $this->services->get('session');
		
		/** @var JSON_Response $json_response */
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
			$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
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

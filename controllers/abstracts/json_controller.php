<?php

namespace Controllers\Abstracts;

use \DCMS\JSON_Response;

abstract class JSON_Controller extends Controller {
	
	/** @var JSON_Response $json_response */
	protected $json_response;
	
	protected function init(){
		$this->json_response = $this->services->get('json_response');
	}
	
	function require_authorization(){
		if(!$this->services->get('session')->get('editor')){
			$this->json_response->fail(JSON_Response::MESSAGE_NO_AUTHORIZATION);
		}
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
	
	// todo: rename parameters
	protected function handle_query_result($result, array $results = null){
		if($result === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		$this->json_response->succeed($results);
	}
	
	protected function handle_update_result($affected_rows, array $results = null){
		if($affected_rows === false){
			$this->json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		if($affected_rows === 0){
			$this->json_response->fail(JSON_Response::MESSAGE_NOTHING_AFFECTED);
		}
		
		$this->json_response->succeed($results);
	}
	
}


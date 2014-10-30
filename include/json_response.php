<?php

namespace DCMS;

class JSON_Response {
	
	const MESSAGE_NO_AUTHORIZATION     = 'no authorization';
	const MESSAGE_NO_PARAMETER         = 'no parameter';
	const MESSAGE_QUERY_FAILURE        = 'query failure';
	const MESSAGE_WRONG_CREDENTIALS    = 'wrong credentials';
	const MESSAGE_UNRECOGNIZED_ACTION  = 'unrecognized action';
	const MESSAGE_NOTHING_AFFECTED     = 'nothing affected';

	//------------------------------------------------
	// returning success
	//------------------------------------------------
	
	function succeed(array $results = null) {
		$output = '';
		
		$output .= '{';
		$output .= '"status":"success"';
		
		if(is_array($results)){
			$output .= $this->process_array($results);
		}
		
		$output .= '}';
		
		header('Content-Type: application/json');
		exit($output);
	}
	
	//------------------------------------------------
	// returning failure
	//------------------------------------------------
	
	function fail($message, array $results = null) {
		$output =
			'{' .
			'"status":"failure"' .
			',' .
			'"message":"' . $message . '"';
		
		if(is_array($results)){
			$output .= $this->process_array($results);
		}
		
		$output .=
			'}';
		
		header('Content-Type: application/json');
		exit($output);
	}
	
	//------------------------------------------------
	
	function process_array(array $results){
		$output = '';
		
		foreach($results as $result_key => $result_value){
			$output .= ',"' . $result_key . '":"' . $result_value . '"';
		}
		
		return $output;
	}
	
	//------------------------------------------------
	
	// not used yet
	// todo: rename
	function return_array(array $data){
		header('Content-Type: application/json');
		exit(JSON_encode($data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE));
	}
	
}


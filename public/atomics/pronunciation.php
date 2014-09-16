<?php

//====================================================
// Atomic operations
// Pronunciation
//====================================================

require '_authorized_header.php';

use \DCMS\JSON_Response;
use \DCMS\Request;

/** @var Request $request */
$request = $services->get('request');

/** @var JSON_Response $json_response */
$json_response = $services->get('json_response');

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$pronunciation_id = $request->get_parameter('id');
if($pronunciation_id === false){
	$json_response->fail('no parameter');
}

$action = $request->get_parameter('a');
if($action === false){
	$json_response->fail('no parameter');
}

if($action == 'update'){
	$text = $request->get_parameter('t');
	if($text === false){
		$json_response->fail('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$parameters = [];

switch($action){
	
	case 'update':
		
		// parsing
		$xsampa = new \Phonetics\XSAMPA_Parser();
		$text = $xsampa->parse($text);
		
		// feedback
		$parameters['value'] = $text;
		
		$affected_rows = $services->get('data')->access('pronunciation')->update($pronunciation_id, $text);
		
		break;
	
	case 'move_up':
		$affected_rows = $services->get('data')->access('pronunciation')->move_up($pronunciation_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('pronunciation')->move_down($pronunciation_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('pronunciation')->delete($pronunciation_id);
		break;
	
	default:
		$json_response->fail(JSON_Response::MESSAGE_UNRECOGNIZED_ACTION);
		break;
	
}

if($affected_rows === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

if($affected_rows === 0){
	$json_response->fail(JSON_Response::MESSAGE_NOTHING_AFFECTED);
}

//----------------------------------------------------
// returning success
//----------------------------------------------------

$json_response->succeed($parameters);


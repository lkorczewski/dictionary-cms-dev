<?php

//====================================================
// Atomic operations
// Headword
//====================================================

require '_authorized_header.php';

use \DCMS\Request;
use \DCMS\JSON_Response;

/** @var Request $request */
$request = $services->get('request');

/** @var JSON_Response $json_response */
$json_response = $services->get('json_response');

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$headword_id = $request->get_parameter('id');
if($headword_id === null){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$action = $request->get_parameter('a');
if($action === null){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

if($action == 'update'){
	$text = $request->get_parameter('t');
	if($text === null){
		$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	
	case 'update':
		$affected_rows = $services->get('data')->access('headword')->update($headword_id, $text);
		break;
	
	case 'move_up':
		$affected_rows = $services->get('data')->access('headword')->move_up($headword_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('headword')->move_down($headword_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('headword')->delete($headword_id);
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

// returning OK

$json_response->succeed();


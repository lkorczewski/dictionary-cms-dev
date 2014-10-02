<?php

//====================================================
// Atomic operations
// Sense
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

$node_id = $request->get_parameter('n');
if($node_id === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$action = $request->get_parameter('a');
if($action === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

if($action == 'add_context'){
	$text = $request->get_parameter('t', '...');
	if($text === false){
		$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	
	case 'move_up':
		$affected_rows = $services->get('data')->access('sense')->move_up($node_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('sense')->move_down($node_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('sense')->delete($node_id);
		break;
	
	case 'add_context':
		$affected_rows = $services->get('data')->access('context')->set($node_id, $text);
		break;
	
	default:
		$json_response->fail(JSON_Response::MESSAGE_UNRECOGNIZED_ACTION);
		break;
	
}

//----------------------------------------------------
// failure handling
//----------------------------------------------------

if($affected_rows === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

if($affected_rows === 0){
	$json_response->fail(JSON_Response::MESSAGE_NOTHING_AFFECTED);
}

// returning result
// NOTICE! set_context returns true that becames 1
//  maybe the result should be number of affected rows?

$json_response->succeed();


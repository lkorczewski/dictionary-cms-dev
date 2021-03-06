<?php

//====================================================
// Atomic operations
// Form
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

$form_id = $request->get_parameter('id');
if($form_id === null)
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);

$action = $request->get_parameter('a');
if($action === null)
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);

if($action == 'update'){
	
	$label = $request->get_parameter('l');
	if($label === null){
		$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
	}
	
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
		$affected_rows = $services->get('data')->access('form')->update($form_id, $label, $text);
		break;
	
	case 'move_up':
		$affected_rows = $services->get('data')->access('form')->move_up($form_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('form')->move_down($form_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('form')->delete($form_id);
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
// returning OK
//----------------------------------------------------

$json_response->succeed();


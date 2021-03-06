<?php

//====================================================
// Atomic operation
// Category label
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

$category_label_id = $request->get_parameter('id');
if($category_label_id === null){
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
		$rows_affected = $services->get('data')->access('category_label')->update($category_label_id, $text);
		break;
	case 'delete':
		$rows_affected = $services->get('data')->access('category_label')->delete($category_label_id);
		break;
	default:
		$json_response->fail(JSON_Response::MESSAGE_UNRECOGNIZED_ACTION);
		break;
}

if($rows_affected === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

if($rows_affected === 0){
	$json_response->fail(JSON_Response::MESSAGE_NOTHING_AFFECTED);
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

$json_response->succeed();

<?php

//====================================================
// Atomic operation
// Headword node
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

$node_id = $request->get_parameter('n');
if($node_id === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$action = $request->get_parameter('a');
if($action === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$text = $request->get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	case 'add_headword':
		$result = $services->get('data')->access('headword')->add($node_id, $text);
		$result_name = 'headword_id';
		break;
	case 'add_pronunciation':
		$result = $services->get('data')->access('pronunciation')->add($node_id, $text);
		$result_name = 'pronunciation_id';
		break;
	case 'add_category_label':
		$result = $services->get('data')->access('category_label')->set($node_id, $text);
		$result_name = 'category_label_id';
		break;
	default:
		$json_response->fail(JSON_Response::MESSAGE_UNRECOGNIZED_ACTION);
		break;
}

if($result === false){
	$json_response->fail('query failure');
}

//----------------------------------------------------
// returning result
//----------------------------------------------------

$json_response->succeed([
	$result_name  => $result
]);


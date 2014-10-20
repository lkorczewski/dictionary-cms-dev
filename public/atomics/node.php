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
if($node_id === null){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$action = $request->get_parameter('a');
if($action === null){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$text = $request->get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$parameters = [];

switch($action){
	
	case 'add_headword':
		$result = $services->get('data')->access('headword')->add($node_id, $text);
		
		$parameters['headword_id'] = $result;
		break;
	
	case 'add_pronunciation':
		
		// parsing
		$xsampa = new \Phonetics\XSAMPA_Parser();
		$text = $xsampa->parse($text);
		
		$result = $services->get('data')->access('pronunciation')->add($node_id, $text);
		
		$parameters = [
			'pronunciation_id' => $result,
			'value'            => $text,
		];
		break;
	
	case 'add_category_label':
		$result = $services->get('data')->access('category_label')->set($node_id, $text);
		
		$parameters['category_label_id'] = $result;
		break;
	
	case 'add_context':
		$result = $services->get('data')->access('context')->set($node_id, $text);
		
		$parameters['context_id'] = $result;
		break;
	
	case 'add_translation':
		$result = $services->get('data')->access('translation')->add($node_id, $text);
		
		$parameters['translation_id'] = $result;
		break;
	
	case 'add_phrase':
		$result = $services->get('data')->access('phrase')->add($node_id, $text);
		
		$parameters['node_id'] = $result;
		break;
	
	case 'add_sense':
		$result = $services->get('data')->access('sense')->add($node_id);
		
		$parameters = [
			'node_id'  => $result,
			'label'    => $label,
		];
		break;
	
	default:
		$json_response->fail(JSON_Response::MESSAGE_UNRECOGNIZED_ACTION);
		break;
}

if($result === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

//----------------------------------------------------
// returning result
//----------------------------------------------------

$json_response->succeed($parameters);

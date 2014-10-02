<?php

//====================================================
// Atomic operation
// Adding form
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

$parent_node_id = $request->get_parameter('n');
if($parent_node_id === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$label = $request->get_parameter('l', '...');

$form = $request->get_parameter('h', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$form_id = $services->get('data')->access('form')->add($parent_node_id, $label, $form);

if($form_id === false){
	$json_response->fail('query failure');
}

//----------------------------------------------------
// returning inserted form id
//----------------------------------------------------

$json_response->succeed([
	'form_id' => $form_id
]);


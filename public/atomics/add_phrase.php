<?php

//====================================================
// Atomic operation
// Adding phrase
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

$phrase = $request->get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $services->get('data')->access('phrase')->add($parent_node_id, $phrase);

if($node_id === false){
	$json_response->fail('query failure');
}

//----------------------------------------------------
// returning id of new node
//----------------------------------------------------

$json_response->succeed([
	'node_id' => $node_id
]);


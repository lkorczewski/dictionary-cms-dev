<?php

//====================================================
// Atomic operation
// Adding sense
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
if($parent_node_id === false)
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $services->get('data')->access('sense')->add($parent_node_id);
$sense_label = $services->get('data')->access('sense')->get_label($node_id);

if($node_id === false){
	$json_response->fail('query failure');
}

//----------------------------------------------------
// returning id of new node
//----------------------------------------------------

$json_response->succeed([
	'node_id'  => $node_id,
	'label'    => $sense_label,
]);


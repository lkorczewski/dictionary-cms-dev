<?php

//====================================================
// Atomic operation
// Deleting entry
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

//----------------------------------------------------
// executing query
//----------------------------------------------------

$success = $services->get('data')->delete_entry($node_id);

if($success === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

$json_response->succeed();


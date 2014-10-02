<?php

//====================================================
// Atomic operation
// Adding entry
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

$headword = $request->get_parameter('h');
if($headword === false)
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);

//----------------------------------------------------
// executing query
//----------------------------------------------------
// returning entry_id, which is probably wrong
// what should it return?

$entry_id = $services->get('data')->access('entry')->add($headword);

if($entry_id === false){
	$json_response->fail('query failure');
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

$json_response->succeed();


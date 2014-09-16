<?php

//====================================================
// Atomic operation
// Adding translation
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

$sense_id = $request->get_parameter('id');
if($sense_id === false)
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);

$text = $request->get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$translation_id = $services->get('data')->access('translation')->add($sense_id, $text);

if($translation_id === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

//----------------------------------------------------
// returning id of new translation
//----------------------------------------------------

$json_response->succeed([
	'translation_id' => $translation_id
]);


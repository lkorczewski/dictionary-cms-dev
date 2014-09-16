<?php

require_once __DIR__ . '/_public_header.php';

use \DCMS\Request;
use \DCMS\JSON_Response;

/** @var Request $request */
$request = $services->get('request');

/** @var JSON_Response $json_response */
$json_response = $services->get('json_response');

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$login = $request->get_parameter('l');
if($login === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

$password = $request->get_parameter('p');
if($password === false){
	$json_response->fail(JSON_Response::MESSAGE_NO_PARAMETER);
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$editor_result = $services->get('dcms_data')->get_editor($login, $password);

if($editor_result === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

if(count($editor_result) == 0){
	$json_response->fail(JSON_Response::MESSAGE_WRONG_CREDENTIALS);
}

//----------------------------------------------------
// registering user
//----------------------------------------------------

$_SESSION['editor'] = $editor_result['login'];

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

$json_response->succeed([
	'editor_name' => $editor_result['login'],
]);


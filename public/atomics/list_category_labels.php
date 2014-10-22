<?php

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

$category_label_search = $request->get_parameter('s');

//----------------------------------------------------
// data acquisition
//----------------------------------------------------

$category_labels = $services->get('data')->access('category_label')->list_all();

if($category_labels === false){
	$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
}

//----------------------------------------------------
// returning result
//----------------------------------------------------

// todo: change response
echo JSON_encode($category_labels, JSON_UNESCAPED_UNICODE);

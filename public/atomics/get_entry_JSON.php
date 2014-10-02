<?php

//====================================================
// Atomic operation
// Getting entry as JSON
//====================================================

require '_authorized_header.php';

require_once 'dictionary/layouts/table_layout.php';

use \DCMS\Request;
use \DCMS\JSON_Response;
use \Dictionary\Table_Layout;

/** @var Request $request */
$request = $services->get('request');

/** @var JSON_Response $json_response */
$json_response = $services->get('json_response');

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = $request->get_parameter('n');
if($node_id === false){
	$json_response->fail('no parameter');
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$entry = $services->get('dictionary')->get_entry_by_id($headword);

if($entry === null){
	$json_response->fail('not found');
}

$layout = new Table_Layout();
$tabular_entry = $layout->parse_entry($entry);

$JSON_entry = JSON_encode($tabular_entry, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

//----------------------------------------------------
// returning JSON-encoded entry
//----------------------------------------------------

echo $JSON_entry;


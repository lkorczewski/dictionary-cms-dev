<?php

//====================================================
// Atomic operation
// Getting entry as JSON
//====================================================

require '_authorized_header.php';

require_once 'dictionary/layouts/table_layout.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false){
	Script::fail('no parameter');
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$entry = $services->get('dictionary')->get_entry_by_id($headword);

if($entry === null){
	Script::fail('not found');
}

$layout = new Table_Layout();
$tabular_entry = $layout->parse_entry($entry);

$JSON_entry = JSON_encode($tabular_entry);

//----------------------------------------------------
// returning JSON-encoded entry
//----------------------------------------------------

echo $JSON_entry;


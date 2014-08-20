<?php

//====================================================
// Atomic operation
// Adding form
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false)
	Script::fail('no parameter');

$label = Script::get_parameter('l', '...');

$form = Script::get_parameter('h', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$form_id = $data->add_form($parent_node_id, $label, $form);

if($form_id === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning inserted form id
//----------------------------------------------------

echo Script::succeed([
	'form_id' => $form_id
]);


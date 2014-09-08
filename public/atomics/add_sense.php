<?php

//====================================================
// Atomic operation
// Adding sense
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false)
	Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $services->get('data')->access('sense')->add($parent_node_id);
$sense_label = $services->get('data')->access('sense')->get_label($node_id);

if($node_id === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning id of new node
//----------------------------------------------------

Script::succeed([
	'node_id'  => $node_id,
	'label'    => $sense_label,
]);


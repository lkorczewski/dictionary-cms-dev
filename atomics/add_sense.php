<?php

//====================================================
// Atomic operation
// Adding sense
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false)
	Script::fail('no parameter');

$label = Script::get_parameter('l', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $data->add_sense($parent_node_id, $label);

if($node_id === false){
	die('query failure');
}

// returning inserted id

echo $node_id;

?>

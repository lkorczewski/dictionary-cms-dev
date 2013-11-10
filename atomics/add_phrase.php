<?php

//====================================================
// Atomic operation
// Adding phrase
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false)
	Script::fail('no parameter');

$phrase = Script::get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $data->add_phrase($parent_node_id, $phrase);

if($node_id === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning id of new node
//----------------------------------------------------

Script::succeed(array('node_id' => $node_id))

?>

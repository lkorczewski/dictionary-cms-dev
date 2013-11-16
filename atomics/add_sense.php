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

$node_id = $data->add_sense($parent_node_id);

if($node_id === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning id of new node
//----------------------------------------------------

Script::succeed(array(
	'node_id' => $node_id,
	'label' => '(x)',
));

?>

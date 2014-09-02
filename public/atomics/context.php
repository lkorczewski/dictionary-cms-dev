<?php

//====================================================
// Atomic operation
// Category label
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false){
	Script::fail('no parameter');
}

$action = Script::get_parameter('a');
if($action === false){
	Script::fail('no parameter');
}

if($action == 'set'){
	$text = Script::get_parameter('t');
	if($text === false){
		Script::fail('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	case 'set':
		$rows_affected = $data->access('context')->set($parent_node_id, $text);
		break;
	case 'delete':
		$rows_affected = $data->access('context')->delete($parent_node_id);
		break;
	default:
		Script::fail('unrecognized action');
		break;
}

if($rows_affected === false){
	Script::fail('query failure');
}

if($rows_affected === 0){
	Script::fail('nothing affected');
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

Script::succeed();

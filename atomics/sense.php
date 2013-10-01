<?php

//====================================================
// Atomic operations
// Sense
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false){
	Script::fail('no parameter');
}

$action = Script::get_parameter('a');
if($action === false){
	Script::fail('no parameter');
}

if($action == 'add_context'){
	$text = Script::get_parameter('t', '...');
	if($text === false){
		Script::fail('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	
	case 'move_up':
		$affected_rows = $data->move_sense_up($node_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_sense_down($node_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_sense($node_id);
		break;
	
	case 'add_context':
		$affected_rows = $data->set_context($node_id, $text);
		break;
	
	default:
		Scritp::fail('unrecognized action');
		break;
	
}

//----------------------------------------------------
// failure handling
//----------------------------------------------------

if($affected_rows === false){
	die('query failure');
}

if($affected_rows === 0){
	die('nothing affected');
}

// returning result
// NOTICE! set_context returns true that becames 1
//  maybe the result should be number of affected rows?

echo 'OK';

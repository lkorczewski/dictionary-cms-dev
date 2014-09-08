<?php

//====================================================
// Atomic operations
// Sense
//====================================================

require '_authorized_header.php';

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
		$affected_rows = $services->get('data')->access('sense')->move_up($node_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('sense')->move_down($node_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('sense')->delete($node_id);
		break;
	
	case 'add_context':
		$affected_rows = $services->get('data')->access('sense')->set_context($node_id, $text);
		break;
	
	default:
		Script::fail('unrecognized action');
		break;
	
}

//----------------------------------------------------
// failure handling
//----------------------------------------------------

if($affected_rows === false){
	Script::fail('query failure');
}

if($affected_rows === 0){
	Script::fail('nothing affected');
}

// returning result
// NOTICE! set_context returns true that becames 1
//  maybe the result should be number of affected rows?

Script::succeed();


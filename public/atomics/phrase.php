<?php

//====================================================
// Atomic operations
// Phrase
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false)
	Script::fail('no parameter');

$action = Script::get_parameter('a');
if($action === false)
	Script::fail('no parameter');

if($action == 'update'){
	$text = Script::get_parameter('t');
	if($text === false){
		Script::fail('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	
	case 'update' :
		$affected_rows = $data->access('phrase')->update($node_id, $text);
		break;
	
	case 'move_up':
		$affected_rows = $data->access('phrase')->move_up($node_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->access('phrase')->move_down($node_id);
		break;
	
	case 'delete':
		$affected_rows = $data->access('phrase')->delete($node_id);
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

// returning OK

Script::succeed();


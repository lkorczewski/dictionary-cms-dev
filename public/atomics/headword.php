<?php

//====================================================
// Atomic operations
// Headword
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$headword_id = Script::get_parameter('id');
if($headword_id === false){
	Script::fail('no parameter');
}

$action = Script::get_parameter('a');
if($action === false){
	Script::fail('no parameter');
}

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
	
	case 'update':
		$affected_rows = $data->update_headword($headword_id, $text);
		break;
	
	case 'move_up':
		$affected_rows = $data->move_headword_up($headword_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_headword_down($headword_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_headword($headword_id);
		break;
	
}

if($affected_rows === false){
	Script::fail('query failure');
}

if($affected_rows === 0){
	Script::fail('nothing affected');
}

// returning OK

Script::succeed();


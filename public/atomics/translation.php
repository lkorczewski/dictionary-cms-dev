<?php

//====================================================
// Atomic operations
// Translation
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$translation_id = Script::get_parameter('id');
if($translation_id === false){
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
		$affected_rows = $services->get('data')->access('translation')->update($translation_id, $text);
		break;
	
	case 'move_up':
		$affected_rows = $services->get('data')->access('translation')->move_up($translation_id);
		break;
	
	case 'move_down':
		$affected_rows = $services->get('data')->access('translation')->move_down($translation_id);
		break;
	
	case 'delete':
		$affected_rows = $services->get('data')->access('translation')->delete($translation_id);
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

//----------------------------------------------------
// returning OK
//----------------------------------------------------

Script::succeed();


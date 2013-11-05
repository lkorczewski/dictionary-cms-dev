<?php

//====================================================
// Atomic operations
// Pronunciation
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$pronunciation_id = Script::get_parameter('id');
if($pronunciation_id === false){
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

$parameters = array();

switch($action){
	
	case 'update':
		
		// parsing
		require_once 'phonetics/XSAMPA_parser.php';
		$xsampa = new \Phonetics\XSAMPA_Parser();
		$text = $xsampa->parse($text);
		
		// feedback
		$parameters['value'] = $text;
		
		$affected_rows = $data->update_pronunciation($pronunciation_id, $text);
		
		break;
	
	case 'move_up':
		$affected_rows = $data->move_pronunciation_up($pronunciation_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_pronunciation_down($pronunciation_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_pronunciation($pronunciation_id);
		break;
	
}

if($affected_rows === false){
	Script::fail('query failure');
}

if($affected_rows === 0){
	Script::fail('nothing affected');
}

// returning success

Script::succeed($parameters);

?>

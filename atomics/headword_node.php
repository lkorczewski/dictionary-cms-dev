<?php

//====================================================
// Atomic operation
// Headword node
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

$text = Script::get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	case 'add_headword':
		$result = $data->add_headword($node_id, $text);
		$result_name = 'headword_id';
		break;
	case 'add_pronunciation':
		$result = $data->add_pronunciation($node_id, $text);
		$result_name = 'pronunciation_id';
		break;
	case 'add_category_label':
		$result = $data->set_category_label($node_id, $text);
		$result_name = 'category_label_id';
		break;
	default:
		Scritp::fail('unrecognized action');
		break;
}

if($result === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning result
//----------------------------------------------------

Script::succeed([$result_name => $result]);


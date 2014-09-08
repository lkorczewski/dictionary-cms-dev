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
		$result = $services->get('data')->access('headword')->add($node_id, $text);
		$result_name = 'headword_id';
		break;
	case 'add_pronunciation':
		$result = $services->get('data')->access('pronunciation')->add($node_id, $text);
		$result_name = 'pronunciation_id';
		break;
	case 'add_category_label':
		$result = $services->get('data')->access('category_label')->set($node_id, $text);
		$result_name = 'category_label_id';
		break;
	default:
		Script::fail('unrecognized action');
		break;
}

if($result === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning result
//----------------------------------------------------

Script::succeed([$result_name => $result]);


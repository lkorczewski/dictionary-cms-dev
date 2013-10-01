<?php

//====================================================
// Atomic operation
// Headword node
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

$text = Script::get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	case 'add_headword':
		$result = $data->add_headword($node_id, $text);
		break;
	case 'add_category_label':
		$result = $data->set_category_label($node_id, $text);
		break;
	default:
		Scritp::fail('unrecognized action');
		break;
}

if($result === false){
	die('query failure');
}

// returning result

echo $result;

?>

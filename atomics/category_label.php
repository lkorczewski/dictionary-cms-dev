<?php

//====================================================
// Atomic operation
// Category label
//====================================================

require '_atomic_header.php';

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
		$rows_affected = $data->set_category_label($parent_node_id, $text);
		break;
	case 'delete':
		$rows_affected = $data->delete_category_label($parent_node_id);
		break;
	default:
		Script::fail('unrecognized action');
		break;
}

if($rows_affected === false){
	die('query failure');
}

if($rows_affected === 0){
	die('nothing affected');
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

echo 'OK';

?>

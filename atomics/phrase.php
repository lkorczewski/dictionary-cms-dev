<?php

//====================================================
// Atomic operations
// Phrase
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false) Script::fail('no parameter');

$action = Script::get_parameter('a');
if($action === false) Script::fail('no parameter');

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
		$affected_rows = $data->update_phrase($node_id);
		break;
	
	case 'move_up':
		$affected_rows = $data->move_phrase_up($node_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_phrase_down($node_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_phrase($node_id);
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

// returning OK

echo 'OK';

?>

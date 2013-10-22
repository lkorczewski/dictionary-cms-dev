<?php

//====================================================
// Atomic operations
// Form
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$form_id = Script::get_parameter('id');
if($form_id === false)
	Script::fail('no parameter');

$action = Script::get_parameter('a');
if($action === false)
	Script::fail('no parameter');

if($action == 'update'){
	
	$label = Script::get_parameter('l');
	if($label === false){
		Script::fail('no parameter');
	}
	
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
		$affected_rows = $data->update_form($form_id, $label, $text);
		break;
	
	case 'move_up':
		$affected_rows = $data->move_form_up($form_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_form_down($form_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_form($form_id);
		break;
	
}

if($affected_rows === false){
	die('query failure');
}

if($affected_rows === 0){
	die('nothing affected');
}

// returning OK

echo 'OK';

?>

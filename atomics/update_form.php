<?php

//====================================================
// Atomic operation
// Updating form
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$form_id = Script::get_parameter('id');
if($form_id === false) Script::fail('no parameter');

$label = Script::get_parameter('l', '...');

$form = Script::get_parameter('h', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$result = $data->update_form($form_id, $label, $form);

if($result === false){
	die('query failure');
}

// returning OK

echo 'OK';

?>

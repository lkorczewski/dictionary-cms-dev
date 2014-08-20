<?php

//====================================================
// Atomic operation
// Adding translation
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$sense_id = Script::get_parameter('id');
if($sense_id === false)
	Script::fail('no parameter');

$text = Script::get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$translation_id = $data->add_translation($sense_id, $text);

if($translation_id === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning id of new translation
//----------------------------------------------------

Script::succeed([
	'translation_id' => $translation_id
]);


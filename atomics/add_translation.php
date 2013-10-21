<?php

//====================================================
// Atomic operation
// Adding translation
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$sense_id = Script::get_parameter('id');
if($sense_id === false)
	Script::fail('no parameter');

$text = Script::get_parameter('t');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$translation_id = $data->add_translation($sense_id, $text);

if($translation_id === false){
	die('query failure');
}

// returning id of new translation

echo $translation_id;

?>

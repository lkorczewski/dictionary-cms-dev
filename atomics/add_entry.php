<?php

//====================================================
// Atomic operation
// Adding entry
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$headword = Script::get_parameter('h');
if($headword === false)
	Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------
// returning entry_id, which is probably wrong
// what should it return?

$entry_id = $data->add_entry($headword);

if($entry_id === false)	die('query failure');

// returning OK

echo 'OK';

?>

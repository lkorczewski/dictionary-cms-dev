<?php

//====================================================
// Atomic operation
// Deleting entry
//====================================================

require '_authorized_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false){
	Script::fail('no parameter');
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$success = $services->get('data')->delete_entry($node_id);

if($success === false){
	Script::fail('query failure');
}

//----------------------------------------------------
// returning OK
//----------------------------------------------------

Script::succeed();


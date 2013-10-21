<?php

//====================================================
// Atomic operation
// Deleting entry
//====================================================

require '_atomic_header.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$success = $data->delete_entry($node_id);

if($success === false) die('query failure');

//----------------------------------------------------
// returning OK
//----------------------------------------------------

echo 'OK';

?>

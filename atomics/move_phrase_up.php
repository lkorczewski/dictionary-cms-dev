<?php

//====================================================
// Atomic operation
// Moving phrase up
//====================================================

session_start();

if(!isset($_SESSION['editor'])) die('no authorization');

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/data.php';
require_once 'dictionary/mysql_data.php';

$database = Script::connect_to_database();
$data = new MySQL_Data($database);

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$node_id = Script::get_parameter('n');
if($node_id === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$affected_rows = $data->move_phrase_up($node_id);

if($affected_rows === false){
	die('query failure');
}

if($affected_rows === 0){
	die('nothing affected');
}

// returning OK

echo 'OK';

?>

<?php

//====================================================
// Atomic operation
// Moving translation up
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

$translation_id = Script::get_parameter('id');
if($translation_id === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$result = $data->move_translation_up($id);

if($result === false){
	die('query failure');
}

if($result === 0){
	die('nothing affected');
}

// returning OK

echo 'OK';

?>

<?php

//====================================================
// Atomic operation
// Updating translation
//====================================================

session_start();

if(!isset($_SESSION['editor'])){
	die('no authorization');
}

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
if($translation_id === false){
	Script::fail('no parameter');
}

$text = Script::get_parameter('t');
if($text === false){
	Script::fail('no parameter');
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$result = $data->update_translation($translation_id, $text);

if($result === false){
	die('query failure');
}

// returning OK

echo 'OK';

?>

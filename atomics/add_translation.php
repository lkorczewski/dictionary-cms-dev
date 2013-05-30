<?php

//====================================================
// Atomic operation
// Adding translation
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

$sense_id = Script::get_parameter('id');
if($sense_id === false) Script::fail('no parameter');

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

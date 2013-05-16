<?php

//====================================================
// Atomic operation
// Updating translation
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

$text = Script::get_parameter('t', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$result = $data->update_phrase($translation_id, $text);
/*
$query =
	'UPDATE translations' .
	" SET text = '$text'" .
	" WHERE translation_id = $translation_id" .
	';';
$result = $database->query($query);
*/

if($result === false){
	die('query failure');
}

// returning OK

echo 'OK';

?>

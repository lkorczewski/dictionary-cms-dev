<?php

//====================================================
// Atomic operation
// Updating form
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

$form_id = Script::get_parameter('id');
if($form_id === false) Script::fail('no parameter');

$label = Script::get_parameter('l', '...');

$form = Script::get_parameter('h', '...');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$result = $data->update_form($form_id, $label, $form);

if($result === false){
	die('query failure');
}

// returning OK

echo 'OK';

?>

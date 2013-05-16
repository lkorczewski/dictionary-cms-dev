<?php

//====================================================
// Atomic operation
// Form
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

$action = Script::get_parameter('a');
if($action === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

switch($action){
	
	case 'move_up':
		$affected_rows = $data->move_form_up($form_id);
		break;
	
	case 'move_down':
		$affected_rows = $data->move_form_down($form_id);
		break;
	
	case 'delete':
		$affected_rows = $data->delete_form($form_id);
		break;
	
}

if($affected_rows === false){
	die('query failure');
}

if($affected_rows === 0){
	die('nothing affected');
}

// returning OK

echo 'OK';

?>

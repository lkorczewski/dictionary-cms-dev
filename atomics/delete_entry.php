<?php

//====================================================
// Atomic operation
// Deleting entry
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

$entry_id = '';
if(isset($_POST['id'])){
	$entry_id = $_POST['id'];
} else {
	if(isset($_GET['id'])){
		$entry_id = $_GET['id'];
	} else {
		die('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$success = $data->delete_entry($entry_id);

if($success === false) die('query failure');

// returning id of new translation

echo 'OK';

?>

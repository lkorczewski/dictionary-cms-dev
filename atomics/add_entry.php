<?php

//====================================================
// Atomic operation
// Adding entry
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

$headword = '...';
if(isset($_POST['h'])){
	$headword = $_POST['h'];
} else {
	if(isset($_GET['h'])){
		$headword = $_GET['h'];
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$entry_id = $data->add_entry($headword);

if($entry_id === false)	die('query failure');

// returning id of new entry

echo $entry_id;

?>

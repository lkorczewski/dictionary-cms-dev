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

$sense_id = '';
if(isset($_POST['id'])){
	$sense_id = $_POST['id'];
} else {
	if(isset($_GET['id'])){
		$sense_id = $_GET['id'];
	} else {
		die('no parameter');
	}
}

$text = '...';
if(isset($_POST['t'])){
	$text = $_POST['t'];
} else {
	if(isset($_GET['t'])){
		$text = $_GET['t'];
	}
}

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

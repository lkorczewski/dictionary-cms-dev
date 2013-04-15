<?php

//====================================================
// Atomic operation
// Updating translation
//====================================================

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

$id = '';
if(isset($_POST['id'])){
	$id = $_POST['id'];
} else {
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} else {
		die('no parameter');
	}
}

$text = '';
if(isset($_POST['t'])){
	$text = $_POST['t'];
} else {
	if(isset($_GET['t'])){
		$text = $_GET['t'];
	} else {
		die('no parameter');
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$query =
	'UPDATE translations' .
	" SET text = '$text'" .
	" WHERE translation_id = $id" .
	';';
$result = $database->query($query);

if($result === false){
	die('query failure');
}

// returning OK

echo 'OK';

?>

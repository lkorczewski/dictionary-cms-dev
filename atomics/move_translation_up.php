<?php

//====================================================
// Atomic operation
// Moving translation up
//====================================================

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

require_once 'database/database.php';

$database = Script::connect_to_database();


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

//----------------------------------------------------
// executing query
//----------------------------------------------------

$query =
	'UPDATE translations t1, translations t2' .
	' SET t1.order = t2.order, t2.order = t1.order' .
	" WHERE t1.translation_id = $id" .
	'  AND t1.order = t2.order + 1' .
	';';
$result = $database->query($query);

if($result === false){
	die('query failure');
}

if($database->get_affected_rows() === 0){
	die('nothing affected');
}

// returning OK

echo 'OK';

?>

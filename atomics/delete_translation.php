<?php

//====================================================
// Atomic operation
// Deleting translation
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
// it should be much simplier
// maybe combined queries
// maybe the translation should be called by order, not id

$database->start_transaction();

// the order of operations doesn't permit
// a unique (sense_id, order) key that would be useful otherwise
// maybe deleting by order would be better
$query =
	'UPDATE `translations` t1, `translations` t2' .
	' SET t1.`order` = t1.`order` - 1' .
	" WHERE t2.translation_id = $id" .
	'  AND t1.`sense_id` = t2.`sense_id`' .
	'  AND t1.`order` > t2.`order`' .
	';';
$result = $database->query($query);

if($result === false){
	echo $query;
	die('query failure');
}

$query =
	'DELETE FROM `translations`' .
	" WHERE `translation_id` = $id" .
	';';
$result = $database->query($query);

if($result === false){
	die('query failure');
}

$database->commit_transaction();

// returning OK

echo 'OK';

?>

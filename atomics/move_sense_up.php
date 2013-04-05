<?php

//====================================================
// Atomic operation
// Moving sense up
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
	'UPDATE senses s1, senses s2' .
	' SET s1.order = s2.order, s2.order = s1.order, s1.label = s2.label, s2.label = s1.label' .
	" WHERE s1.sense_id = $id" .
	'  AND s1.parent_sense_id = s2.parent_sense_id' . // ugly database structure
	'  AND s1.entry_id = s2.entry_id' . // ugly database structure
	'  AND s1.order = s2.order + 1' .
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

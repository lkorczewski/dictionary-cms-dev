<?php

//====================================================
// Atomic operation
// Adding translation
//====================================================

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

require_once 'database/database.php';

$database = Script::connect_to_database();


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

$query =
	'INSERT `translations` (`sense_id`, `order`, `text`)' .
	" SELECT $sense_id, MAX(`new_order`), '$text'" .
	'  FROM (' .
	'   SELECT MAX(`order`) AS `new_order`' .
	'    FROM `translations`' .
	"    WHERE `sense_id` = $sense_id" .
	'    GROUP BY `sense_id`' .
	'   UNION SELECT 1 AS `new_order`' .
	'  ) t' .
	';';
$result = $database->query($query);

if($result === false){
	die('query failure');
}

$query = 'SELECT last_insert_id() AS `insert_id`;';
$result = $database->query($query);

if($result === false){
	die('query failure');
}

// returning inserted id

echo $result[0]['insert_id'];

?>

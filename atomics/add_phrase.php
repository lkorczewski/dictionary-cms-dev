<?php

//====================================================
// Atomic operation
// Adding phrase
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

$parent_node_id = '';
if(isset($_POST['n'])){
	$parent_node_id = $_POST['n'];
} else {
	if(isset($_GET['n'])){
		$parent_node_id = $_GET['n'];
	} else {
		die('no parameter');
	}
}

$phrase = '...';
if(isset($_POST['t'])){
	$phrase = $_POST['t'];
} else {
	if(isset($_GET['t'])){
		$phrase = $_GET['t'];
	}
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$node_id = $data->add_phrase($parent_node_id, $phrase);

if($node_id === false){
	die('query failure');
}

// returning inserted id

echo $node_id;

?>

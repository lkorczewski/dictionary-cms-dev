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

$parent_node_id = Script::get_parameter('n');
if($parent_node_id === false) Script::fail('no parameter');

$phrase = Script::get_parameter('t', '...');

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

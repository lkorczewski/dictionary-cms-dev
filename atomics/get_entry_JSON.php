<?php

//====================================================
// Atomic operation
// Getting entry as JSON
//====================================================

//----------------------------------------------------
// session
//----------------------------------------------------

session_start();

if(!isset($_SESSION['editor'])) die('no authorization');

//----------------------------------------------------
// including libraries
//----------------------------------------------------

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';
require_once 'dictionary/entry.php';
require_once 'dictionary/layouts/table_layout.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$headword = Script::get_parameter('h');
if($headword === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$database = Script::connect_to_database();
$data = new MySQL_Data($database);

$dictionary = new Dictionary($data);
$entry = $dictionary->get_entry($headword);

if($entry === null){
	Script::fail('not found');
}

$layout = new Table_Layout();
$tabular_entry = $layout->parse_entry($entry);

$JSON_entry = JSON_encode($tabular_entry);

//----------------------------------------------------
// returning JSON-encoded entry
//----------------------------------------------------

echo $JSON_entry;

?>

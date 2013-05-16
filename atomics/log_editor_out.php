<?php

session_start();

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

// no parameters...

//----------------------------------------------------
// executing query
//----------------------------------------------------

// no query...

//----------------------------------------------------
// registering user
//----------------------------------------------------

unset($_SESSION['editor']);

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

$output =
	'{' .
	'"status":"OK"' .
	'}';

echo $output;

?>

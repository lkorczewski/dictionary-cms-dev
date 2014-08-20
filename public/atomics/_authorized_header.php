<?php

require_once __DIR__ . '/../../include/script.php';

Script::set_root_path(__DIR__ . '/../..');
$config = Script::load_config();

Script::start_session();

if(!isset($_SESSION['editor'])){
	Script::fail('no authorization');
}

require_once 'database/database.php';
require_once 'dictionary/data.php';
require_once 'dictionary/mysql_data.php';

$database = Script::connect_to_database();
$data = new \Dictionary\MySQL_Data($database);


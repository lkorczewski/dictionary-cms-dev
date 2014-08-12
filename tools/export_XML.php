#!/usr/bin/php
<?php

//====================================================
// initialization
//====================================================

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';
require_once 'dictionary/layouts/XML_layout.php';

$database = Script::connect_to_database();

$data = new Dictionary\MySQL_Data($database);
$dictionary = new Dictionary\Dictionary($data);

//====================================================
// parsing dictionary
//====================================================

$layout = new Dictionary\XML_Layout();
$layout->parse_dictionary($dictionary, 'php://stdout');

if($db_error = $database->get_last_error()){
	fwrite(STDERR, $db_error['message'] . "\n" . print_r($db_error, true));
}


#!/usr/bin/php
<?php

use Dictionary\XML_Layout;

//====================================================
// initialization
//====================================================

require __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
Script::load_config();

require_once 'dictionary/layouts/XML_layout.php';

//====================================================
// parsing dictionary
//====================================================

$database    = $services->get('database');
$dictionary  = $services->get('dictionary');

$layout = new XML_Layout();
$layout->parse_dictionary($dictionary, 'php://stdout');

if($db_error = $database->get_last_error()){
	fwrite(STDERR, $db_error['message'] . "\n");
}


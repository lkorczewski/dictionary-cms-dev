#!/usr/bin/php
<?php

use Dictionary\XML_Importer;

//====================================================
// initialization
//====================================================

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
Script::load_config();

require_once 'dictionary/import/XML_importer.php';

require 'bootstrap.php';

//====================================================
// parsing
//====================================================

$XML_file = $argv[1];

$data      = $services->get('data');
$database  = $services->get('database');

$importer = new XML_Importer($data);
$importer->parse($XML_file);

if($error = $database->get_last_error()){
	fwrite(STDERR, $error['message'] . "\n");
}


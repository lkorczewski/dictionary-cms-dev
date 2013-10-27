#!/usr/bin/php
<?php

//====================================================
// initialization
//====================================================

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/import/XML_importer.php;'

$database = Script::connect_to_database();

$data = new MySQL_Data($database);

//====================================================
// parsing
//====================================================

$XML_file = $argv[1];

$importer = new Dictionary\XML_Importer($data);
$importer->parse($XML_file);

?>

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
require_once 'dictionary/dictionary.php';
require_once 'dictionary/layouts/XML_layout.php';

$database = Script::connect_to_database();

$data = new MySQL_Data($database);
$dictionary = new Dictionary($data);

//====================================================
// parsing dictionary
//====================================================

$layout = new Dictionary\XML_Layout();
$layout->parse_dictionary($dictionary, 'php://stdout');

?>

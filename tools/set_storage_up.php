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
require_once 'include/data.php';

// unsuccessful connection should result in an error too

$database         = Script::connect_to_database();
$dictionary_data  = new \Dictionary\MySQL_Data($database);
$dcms_data        = new \DCMS\Data($database);

//====================================================
// creating storage
//====================================================

$result                = true;
$database_error        = false;
$storage_creation_log  = [];

if($result === true){
	$result = $dictionary_data->create_storage($storage_creation_log);
}

if($result === true){
	$result = $dcms_data->create_storage($storage_creation_log);
}

//====================================================
// displaying log
//====================================================

foreach($storage_creation_log as $log_entry){
	$line =
		$log_entry['action'] .
		' : ' .
		( $log_entry['result'] ? 'OK' : 'failure' ) .
		"\n";
	echo $line;
}

if($result === false){
	$database_error = $database->get_last_error();
	echo
		'Error while creating storage: ' .
		$database_error['message'] .
		"\n";
	exit;
}

echo 'Storage successfully set up.' . "\n";


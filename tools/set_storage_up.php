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

$database = Script::connect_to_database();
$data = new \Dictionary\MySQL_Data($database);

//====================================================
// creating storage
//====================================================

$result = $data->create_storage($log);

//====================================================
// displaying log
//====================================================

foreach($log as $log_entry){
	$line =
		$log_entry['action'] .
		' : ' .
		( $log_entry['result'] ? 'OK' : 'failure' ) .
		"\n";
	echo $line;
}

if($result === false){
	echo 'Error while creating storage.' . "\n";
	exit;
}

echo 'Storage sucessfully set up.' . "\n";

?>

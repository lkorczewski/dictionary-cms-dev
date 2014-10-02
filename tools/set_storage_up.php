#!/usr/bin/php
<?php

//====================================================
// initialization
//====================================================

require __DIR__ . '/../bootstrap.php';

//====================================================
// creating storage
//====================================================

$result                = true;
$database_error        = false;
$storage_creation_log  = [];

if($result === true){
	$result = $services->get('data')->create_storage($storage_creation_log);
}

if($result === true){
	$result = $services->get('dcms_data')->create_storage($storage_creation_log);
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
	$database_error = $this->get('database')->get_last_error();
	echo
		'Error while creating storage: ' .
		$database_error['message'] .
		"\n";
	exit;
}

echo 'Storage successfully set up.' . "\n";


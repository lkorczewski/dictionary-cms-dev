#!/usr/bin/php
<?php

//====================================================
// initialization
//====================================================

require __DIR__ . '/../bootstrap.php';

//====================================================
// listing all tables
//====================================================

$query = 'SHOW TABLES;';
$tables_result = $services->get('database')->fetch_column($query);

if($tables_result === false){
	exit('Error while listing tables.' . "\n");
}

if($tables_result === []){
	exit('Database is already empty.' . "\n");
}

//====================================================
// disabling foreign key checks
//====================================================

$query = 'SET FOREIGN_KEY_CHECKS=0';
$disable_keys_result = $services->get('database')->execute($query);

if($disable_keys_result === false){
	exit('Error while disabling foreign key checks.' . "\n");
}

//====================================================
// deleting tables
//====================================================

$tables_sql = implode(', ', $tables_result);
$query = "DROP TABLE $tables_sql;";
$drop_table_result = $services->get('database')->execute($query);

if($drop_table_result === false){
	exit('Error while deleting tables.' . "\n");
}

//====================================================
// success handling
//====================================================

exit('Success!' . "\n");


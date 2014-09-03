#!/usr/bin/php
<?php

// initialization

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
$config    = Script::load_config();
$database  = Script::connect_to_database();

// listing all tables

$query = 'SHOW TABLES;';
$tables_result = $database->fetch_column($query);

if($tables_result === false){
	exit('Error while listing tables.' . "\n");
}

if($tables_result === []){
	exit('Database is already empty.' . "\n");
}

// disabling foreign key checks

$query = 'SET FOREIGN_KEY_CHECKS=0';
$disable_keys_result = $database->execute($query);

if($disable_keys_result === false){
	exit('Error while disabling foreign key checks.' . "\n");
}

// deleting tables

$tables_sql = implode(', ', $tables_result);
$query = "DROP TABLE $tables_sql;";
$drop_table_result = $database->execute($query);

if($drop_table_result === false){
	exit('Error while deleting tables.' . "\n");
}

exit('Success!' . "\n");


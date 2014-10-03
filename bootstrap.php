<?php

//----------------------------------------------------
// config
//----------------------------------------------------
$config = require __DIR__ . '/config.php';

//----------------------------------------------------
// autoloader
//----------------------------------------------------
require_once __DIR__ . '/' .$config['include_path'] . '/core/autoloader.php';
\Core\Autoloader::register(__DIR__ . '/' . $config['include_path']);

//----------------------------------------------------
// service container
//----------------------------------------------------
$services = require __DIR__ . '/services.php';

//----------------------------------------------------
// debugging
//----------------------------------------------------
if($services->get('config')->get('debug')){
	ini_set('display_errors',   1);
	ini_set('error_reporting',  E_ALL);
}


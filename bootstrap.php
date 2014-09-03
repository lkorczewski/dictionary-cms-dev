<?php

// autoloader
// todo: path should be based on config!
require_once __DIR__ . '/../../library/core/autoloader.php';
\Core\Autoloader::register(__DIR__ . '/../../library');

// service container
$services = require __DIR__ . '/services.php';


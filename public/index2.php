<?php

require __DIR__ . '/../bootstrap.php';

// legacy configuration

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
Script::load_config();

// executing routing

$services->get('router')->route();


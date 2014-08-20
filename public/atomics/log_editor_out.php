<?php

require_once __DIR__ . '/../../include/script.php';

Script::set_root_path(__DIR__ . '/../..');
$config = Script::load_config();

Script::start_session();

//----------------------------------------------------
// unregistering user
//----------------------------------------------------

unset($_SESSION['editor']);

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

Script::succeed();


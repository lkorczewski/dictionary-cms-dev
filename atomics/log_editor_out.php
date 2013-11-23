<?php

require_once '../include/script.php';

Script::set_root_path('..');
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

?>

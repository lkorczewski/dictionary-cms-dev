<?php

require_once '../include/script.php';

Script::start_session();

//----------------------------------------------------
// unregistering user
//----------------------------------------------------

unset($_SESSION['editor']);

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

$output =
	'{' .
	'"status":"OK"' .
	'}';

echo $output;

?>

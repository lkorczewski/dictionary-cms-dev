<?php

require_once __DIR__ . '/_public_header.php';

//----------------------------------------------------
// unregistering user
//----------------------------------------------------

unset($_SESSION['editor']);

// $this->get('session')->destroy();

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

$services->get('json_response')->succeed();


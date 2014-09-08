<?php

require_once __DIR__ . '/_public_header.php';

require_once 'include/data.php';

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$login = Script::get_parameter('l');
if($login === false){
	Script::fail('no parameter');
}

$password = Script::get_parameter('p');
if($password === false){
	Script::fail('no parameter');
}

//----------------------------------------------------
// executing query
//----------------------------------------------------

$editor_result = $services->get('dcms_data')->get_editor($login, $password);

if($editor_result === false){
	Script::fail('query failure');
}

if(count($editor_result) == 0){
	Script::fail('wrong credentials');
}

//----------------------------------------------------
// registering user
//----------------------------------------------------

$_SESSION['editor'] = $editor_result['login'];

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

Script::succeed([
	'editor_name' => $editor_result['login'],
]);


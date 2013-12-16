<?php

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

Script::start_session();

require_once '../include/data.php';

$database = Script::connect_to_database();

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

$data = new \DCMS\Data($database);

$editor_result = $data->get_editor($login, $password);

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


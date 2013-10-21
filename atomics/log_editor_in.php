<?php

require_once '../include/script.php';

Script::set_root_path('..');
$config = Script::load_config();

Script::start_session();

require_once 'database/database.php';

$database = Script::connect_to_database();

//----------------------------------------------------
// setting parameters
//----------------------------------------------------

$login = Script::get_parameter('l');
if($login === false) Script::fail('no parameter');

$password = Script::get_parameter('p');
if($password === false) Script::fail('no parameter');

//----------------------------------------------------
// executing query
//----------------------------------------------------

$hashed_password = sha1($password);

$query =
	'SELECT *' .
	' FROM editors' .
	" WHERE login = '$login'" .
	"  AND password = '$hashed_password'" .
	';';
$result = $database->query($query);

if($result === false) Script::fail('query failure');

if(count($result) == 0) Script::fail('wrong credentials');

$editor = $result[0];

//----------------------------------------------------
// registering user
//----------------------------------------------------

$_SESSION['editor'] = $editor['login'];

//----------------------------------------------------
// returning confirmation
//----------------------------------------------------

$output =
	'{' .
	'"status":"OK"' .
	',' .
	'"editor_name":"'.$editor['login'].'"' .
	'}';

echo $output;

?>

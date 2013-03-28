<?php

// configuration

$config = array();

require 'config.php';

// debug

if(isset($config['debug']) && $config['debug'] == true){
	ini_set('display_errors',1);
	ini_set('error_reporting', E_ALL);
}

// classes

require_once 'include/database.php';

$db = new Database();
if(isset($config['db_host'])) $db->set_host($config['db_host']);
if(isset($config['db_port'])) $db->set_port($config['db_port']);
if(isset($config['db_user'])) $db->set_user($config['db_user'],
	isset($config['db_password'])?$config['db_password']:''
);
if(isset($config['db_database'])) $db->set_database($config['db_database']);
$db->connect();
?>
<html>
<head>
<title>Dictionary</title>
</head>
<body>
<?php
// example

$headword = isset($_GET['h']) ? $_GET['h'] : '';

switch($headword){
	
	// list of headwords
	case '':
	
		$result = $db->query('select headword from entries;');
		
		foreach($result as $row){
			echo "<p><a href=\"?h={$row['headword']}\">{$row['headword']}</p>\n";
		}
		
		break;
	
	default :
		
		echo '<p>searching not implemented</p>';
		
		break;
}

?>

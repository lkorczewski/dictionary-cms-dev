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

if(isset($config['include_path'])){
	set_include_path(get_include_path() . PATH_SEPARATOR . $config['include_path']);
}

require_once 'database/database.php';
require_once 'include/dictionary.php';

// database initialization

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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
		
		$dictionary = new Dictionary($db);
		$entry = $dictionary->get_entry($headword);
		
		break;
}

?>
</body>
</html>

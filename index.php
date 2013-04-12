<?php

require_once 'include/script.php';

$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';
require_once 'include/layout.php';

$database = Script::connect_to_database();

//====================================================
// content construction
//====================================================

$headword = isset($_GET['h']) ? $_GET['h'] : '';

$data = new MySQL_Data($database);
$dictionary = new Dictionary($data);

$content = '';

switch($headword){
	
	// list of headwords
	case '':
		
		$headwords = $data->pull_headwords();
		
		foreach($headwords as $headword){
			$content .= "<p><a href=\"?h=$headword\">$headword</p>\n";
		}
		
		break;
	
	default :
		$entry = $dictionary->get_entry($headword);
		
		$layout = new Layout();
		$content .= $layout->parse($entry);
		
		break;
}

//====================================================
// presentation
//====================================================

?>
<!DOCTYPE html>
<html>
<head>
<title>Dictionary</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="styles/dictionary.css"/>
<script type="text/javascript" src="scripts/editing.js"></script>
</head>
<body>
<?php
	
echo $content;
	
?>
</body>
</html>

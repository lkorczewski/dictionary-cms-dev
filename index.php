<?php

require_once 'include/script.php';

$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/dictionary.php';
require_once 'include/layout.php';

$database = Script::connect_to_database();

//====================================================
// content construction
//====================================================

$content = '';

$headword = isset($_GET['h']) ? $_GET['h'] : '';

switch($headword){
	
	// list of headwords
	case '':
	
		$result = $database->query('select headword from entries;');
		
		foreach($result as $row){
			$content .= "<p><a href=\"?h={$row['headword']}\">{$row['headword']}</p>\n";
		}
		
		break;
	
	default :
		
		$dictionary = new Dictionary($database);
		$entry = $dictionary->get_entry($headword);
		
		$layout = new Layout();
		$content .= $layout->parse($entry);
		
		break;
}

//====================================================
// presentation
//====================================================

?>
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

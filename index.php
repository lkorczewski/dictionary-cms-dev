<?php

// może sesja powinna być wyłączana, jeśli nie ma logowania?
session_start();

require_once 'include/script.php';

$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';

require_once 'include/localization.php';
require_once 'include/layout.php';

$database = Script::connect_to_database();

$localization = new Localization();
$localization->set_path($config['locale_path']);
$localization->set_locale($config['locale']);

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
		
		$headwords = $dictionary->get_headwords();
		
		foreach($headwords as $headword){
			$content .= "<p><a href=\"?h=$headword\">$headword</p>\n";
		}
		
		break;
	
	default :
		
		$entry = $dictionary->get_entry($headword);
		
		$layout = new Layout($localization);
		$content .= $layout->parse($entry);
		
		break;
		
}

//====================================================
// presentation
//====================================================
// is it really an optimal method for caching output?
//====================================================

$output = '';
$output .=
	'<!DOCTYPE html>' . "\n" .
	'<html>' . "\n" .
	'<head>' . "\n" .
	'<title>Dictionary</title>' . "\n" .
	'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>' . "\n" .
	'<link rel="stylesheet" type="text/css" href="styles/dictionary.css"/>' . "\n" .
	'<script type="text/javascript" src="scripts/common.js"></script>' . "\n" .
	'<script type="text/javascript" src="scripts/editing.js"></script>' . "\n" .
	'</head>' . "\n" .
	'<body>' . "\n";

if(!isset($config['hide_toolbar']) || !$config['hide_toolbar']){
	$output .=
		'<div id="toolbar" class="toolbar">' .
		'<div id="editor_toolbar" class="editor_toolbar">';
	
	if(isset($_SESSION['editor'])){
		$output .=
			'<div class="editor">' . $_SESSION['editor'] . '</div>' .
			'<button onclick="logEditorOut(showEditorLogIn)">wyloguj się</button>';
	} else {
		$output .= '<div class="editor_log_in" onclick="showEditorCredentialsInput()">zaloguj się</div>';
	}
	
	$output .=
		'</div>' .
		'</div>' . "\n";
}

$output .= $content;
	
$output .=
	'</body>' .
	'</html>' ;

echo $output;
	
?>

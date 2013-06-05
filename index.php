<?php

// może sesja powinna być wyłączana, jeśli nie ma logowania?
session_start();

require_once 'include/script.php';

$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';

require_once 'include/localization.php';
require_once 'include/edition_layout.php';
require_once 'include/view_layout.php';

$database = Script::connect_to_database();

$localization = new Localization();
$localization->set_path($config['locale_path']);
$localization->set_locale($config['locale']);

//====================================================
// presentation variables
//====================================================

$show_toolbar = true;
if(isset($config['hide_toolbar']) && $config['hide_toolbar'] === true){
	$show_toolbar = false;
}

$editor_logged_in  = isset($_SESSION['editor']);

$mode = 'view';
if(isset($_GET['m']) && $_GET['m'] == 'edition'){
	$mode = 'edition';
}
if(isset($_SESSION['edition_mode']) && $_SESSION['edition_mode'] === true){
	$mode = 'edition';
}

//====================================================
// content construction
//====================================================

$headword = isset($_GET['h']) ? $_GET['h'] : '';

$data = new MySQL_Data($database);
$dictionary = new Dictionary($data);

$content = '';

if($headword == ''){
	
	$headwords = $dictionary->get_headwords();
	
	foreach($headwords as $headword_item){
		$content .= "<p><a href=\"?h=$headword_item\">$headword_item</p>\n";
	}
	
} else {
	
	$entry = $dictionary->get_entry($headword);
	
	if($entry){
		
		switch($mode){
			case 'edition' :
				$layout = new Edition_Layout($localization);
				break;
			case 'view' :
				$layout = new View_Layout($localization);
				break;
		}
		
		$content .= $layout->parse($entry);
		
	}
	
}

//====================================================
// presentation
//====================================================
// is it really an optimal method for caching output?
//====================================================

// header

$output = '';
$output .=
	'<!DOCTYPE html>' . "\n" .
	'<html>' . "\n" .
	'<head>' . "\n" .
	'<title>Dictionary</title>' . "\n" .
	'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>' . "\n" .
	'<link rel="stylesheet" type="text/css" href="styles/dictionary.css"/>' . "\n" .
	'<script type="text/javascript" src="scripts/common.js"></script>' . "\n" .
	'<script type="text/javascript" src="scripts/construction.js"></script>' . "\n" .
	'<script type="text/javascript" src="scripts/editing.js"></script>' . "\n" .
	'</head>' . "\n" .
	'<body>' . "\n";

// login toolbar

if($show_toolbar){
	$output .=
		'<div id="toolbar" class="toolbar">' .
		'<div id="editor_toolbar" class="editor_toolbar">';
	
	if($editor_logged_in){
		$output .=
			'<div class="editor">' . $_SESSION['editor'] . '</div>' .
			'<button class="button" onclick="logEditorOut(showEditorLogIn)">' .
			$localization->get_text('log out') .
			'</button>';
	} else {
		$output .=
			'<div class="editor_log_in" onclick="showEditorCredentialsInput()">' .
			$localization->get_text('log in') .
			'</div>';
	}
	
	$output .=
		'</div>' .
		'</div>' . "\n";
}

// edition toolbar

if($show_toolbar && $editor_logged_in){
	$output .=
		'<div class="edition_toolbar">';
	if($mode == 'edition'){
		$output .=
			'<a class="mode_switch" href="?h=' . $headword . '">' .
			$localization->get_text('view') .
			'</a>';
	} else {
		$output .=
			'<a class="mode_switch" href="?h=' . $headword . '&m=edition">' .
			$localization->get_text('edition') .
			'</a>';
	}
	$output .=
		'</div>' . "\n";
}

// content itself

$output .= $content;

// alternative

if($editor_logged_in && $headword && !$entry){
	$output .=
		'<div>' .
		'Entry doesn\'t exist. Create a new one?' .
		'</div>' .
		'<button onclick="addEntry(\'' . $headword . '\')">' .
		'create' .
		'</button>';
}

// footer
	
$output .=
	'</body>' .
	'</html>' ;

//====================================================

echo $output;
	
?>

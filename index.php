<?php

// może sesja powinna być wyłączana, jeśli nie ma logowania?
session_start();

require_once 'include/script.php';

$config = Script::load_config();

require_once 'database/database.php';
require_once 'dictionary/mysql_data.php';
require_once 'dictionary/dictionary.php';

require_once 'include/view.php';

$database = Script::connect_to_database();

//====================================================
// presentation variables
//====================================================

// should be replaced by some "mode" declaration
// if mode = READ_ONLY, then the log-in toolbar is not shown

$show_toolbar = true;
if(isset($config['hide_toolbar']) && $config['hide_toolbar'] === true){
	$show_toolbar = false;
}

$editor  = isset($_SESSION['editor']) ? $_SESSION['editor'] : ''; // do wyrzucenia

$mode = 'view';
if(isset($_GET['m']) && $_GET['m'] == 'edition'){
	$mode = 'edition';
}
if(isset($_SESSION['edition_mode']) && $_SESSION['edition_mode'] === true){
	$mode = 'edition';
}

//----------------------------------------------------

$search_mask     = isset($_SESSION['search_mask']) ? $_SESSION['search_mask'] : '';
$search_results  = isset($_SESSION['search_results']) ? $_SESSION['search_results'] : false;

//====================================================
// content construction
//====================================================

$headword = isset($_GET['h']) ? $_GET['h'] : '';

$data = new MySQL_Data($database);
$dictionary = new Dictionary($data);

//====================================================

//----------------------------------------------------
// data obtaining and processing
//----------------------------------------------------

if($headword){
	$entries = $dictionary->get_entries($headword);
}

if($search_results == false){
	$search_results = $data->pull_headwords($search_mask, $config['search_results_limit']);
	$_SESSION['search_results'] = $search_results;
}

//----------------------------------------------------
// data for view
//----------------------------------------------------

$data = array(
	'config'          => $config,
	'headword'        => $headword,
	'search_mask'     => $search_mask,
	'search_results'  => $search_results,
	'mode'            => $mode,
	'editor'          => $editor,
	'show_toolbar'    => $show_toolbar,
);

if($headword){
	$data['entries'] = $entries;
}

$view = new \DCMS\View($data);

if($headword == ''){
	$view->show_home_page();
} else {
	$view->show_entries();
}

?>

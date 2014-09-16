<?php

require __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
Script::load_config();

require_once 'include/view.php';

//====================================================
// presentation variables
//====================================================

// should be replaced by some "mode" declaration
// if mode = READ_ONLY, then the log-in toolbar is not shown

$show_toolbar = true;
if($services->get('config')->get('hide_toolbar') === true){
	$show_toolbar = false;
}

$editor = $services->get('session')->get('editor', ''); // do wyrzucenia

$mode = 'view';
if(isset($_GET['m']) && $_GET['m'] == 'edition'){
	$mode = 'edition';
}
if($services->get('config')->get('edition_mode') === true){
	$mode = 'edition';
}

//====================================================
// content
//====================================================

//----------------------------------------------------
// subcontrollers
//----------------------------------------------------

require_once __DIR__ . '/../controllers/entry.php';
use DCMS\Controllers\Entry_Controller;
$entry_controller = new Entry_Controller($services->get('dictionary'));
$entry_data = $entry_controller->execute();

require_once __DIR__ . '/../controllers/search.php';
use DCMS\Controllers\Search_Controller;
$search_controller = new Search_Controller($services->get('dictionary'), $services->get('config'));
$search_data = $search_controller->execute();

//----------------------------------------------------
// data for view
//----------------------------------------------------

$data = [
	'config'        => $services->get('config'),
	'mode'          => $mode,
	'editor'        => $editor,
	'show_toolbar'  => $show_toolbar,
]
	+ $entry_data
	+ $search_data;

//----------------------------------------------------
// view
//----------------------------------------------------

$view = new \DCMS\View($data);

if($data['headword'] == ''){
	$view->show_home_page();
} else {
	$view->show_entries();
}


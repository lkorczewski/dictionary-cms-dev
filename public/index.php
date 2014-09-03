<?php

require __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../include/script.php';

Script::set_root_path(__DIR__ . '/..');
$config = Script::load_config();

Script::start_session();

//require_once 'database/database.php';
//require_once 'dictionary/mysql_data.php';
//require_once 'dictionary/dictionary.php';

require_once 'include/view.php';

//====================================================
// presentation variables
//====================================================

// should be replaced by some "mode" declaration
// if mode = READ_ONLY, then the log-in toolbar is not shown

$show_toolbar = true;
if(isset($config['hide_toolbar']) && $config['hide_toolbar'] === true){
	$show_toolbar = false;
}

$editor = isset($_SESSION['editor']) ? $_SESSION['editor'] : ''; // do wyrzucenia

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

//$database = Script::connect_to_database();
//$data = new \Dictionary\MySQL_Data($database);
//$dictionary = new \Dictionary\Dictionary($data);

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


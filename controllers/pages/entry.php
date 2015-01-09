<?php

namespace Controllers\Pages;

use Controllers\Abstracts\Controller;
use Controllers\Entry as Entry_Controller;
use Controllers\Search as Search_Controller;

class Entry extends Controller {
	
	function show($headword = ''){
		
		//----------------------------------------------------
		// initialization
		//----------------------------------------------------
		
		/** @var \Config\Config $config */
		$config = $this->services->get('config');
		
		/** @var \DCMS\Session $session */
		$session = $this->services->get('session');
		
		/** @var \DCMS\Request $show_toolbar */
		$request = $this->services->get('request');
		
		//----------------------------------------------------
		// parameters
		//----------------------------------------------------
		
		// should be replaced by some "mode" declaration
		// if mode = READ_ONLY, then the log-in toolbar is not shown
		
		$show_toolbar = true;
		if($config->get('hide_toolbar') === true){
			$show_toolbar = false;
		}
		
		$editor = $session->get('editor', ''); // do wyrzucenia
		
		$mode = 'view';
		if($request->get_parameter('m') == 'edition'){
			$mode = 'edition';
		}
		if($config->get('edition_mode') === true){
			$mode = 'edition';
		}
		
		//----------------------------------------------------
		// subcontrollers
		//----------------------------------------------------
		
		$entry_controller = new Entry_Controller($this->services);
		$entry_data = $entry_controller->find($headword);
	
		$search_controller = new Search_Controller($this->services);
		$search_data = $search_controller->search();
		
		//----------------------------------------------------
		// data for view
		//----------------------------------------------------
		
		$data = [
			'mode'          => $mode,
			'editor'        => $editor,
			'show_toolbar'  => $show_toolbar,
		]
			+ $entry_data
			+ $search_data;
		
		// view
		
		require_once 'include/view.php';
		$view = new \DCMS\View($this->services, $data);
		
		if($data['headword'] == ''){
			$view->show_home_page();
		} else {
			$view->show_entries();
		}
		
	}

}

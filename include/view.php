<?php

namespace DCMS;

require_once 'include/localization.php';
require_once 'include/layouts/edition_layout.php';
require_once 'include/layouts/view_layout.php';

require_once 'views/search.php';

use Core\Service_Container;
use DCMS\Views\Search_View;

class View {
	
	/** @var Service_Container $services */
	private $services;
	
	/** @var Localization $localization */
	private $localization;
	
	private $data;
	
	//------------------------------------------------------------------------
	// constructor
	//------------------------------------------------------------------------
	
	public function __construct(Service_Container $services, array $data){
		$this->services  = $services;
		$this->data      = $data;
		
		$this->localization = $this->services->get('localization');
	}
	
	//------------------------------------------------------------------------
	// displaying layout with static page
	//------------------------------------------------------------------------
	
	function show_home_page(){
		$content = '';
		
		$content .=
			'<div id="content_panel">' . "\n";
		$content .= 'Select a headword.';
		$content .=
			'</div>' . "\n";
		
		$this->display_layout($content);
	}
	
	//------------------------------------------------------------------------
	// displaying layout with entries
	//------------------------------------------------------------------------
	
	function show_entries(){
		
		if($this->data['entries']){
			$content = $this->get_entries();
		} else {
			$content = $this->get_entry_not_found();
		}
		
		$this->display_layout($content);
	}
	
	//========================================================================
	// private methods
	//========================================================================
	
	//------------------------------------------------------------------------
	// displaying standard page layout
	//------------------------------------------------------------------------
	
	private function display_layout($content){
		$output = '';
		
		$output .= $this->get_header();
		
		if($this->data['show_toolbar']){
			$output .= $this->get_editor_toolbar();
		}
		
		$output .= $this->get_title();
		
		if($this->data['show_toolbar'] && $this->data['editor']){
			$output .= $this->get_mode_toolbar();
		}
		
		$output .= $this->get_search_panel();
		
		$output .= $content;
		
		$output .= $this->get_footer();
		
		echo $output;
	}
	
	//------------------------------------------------------------------------
	// HTML header template
	//------------------------------------------------------------------------
	
	private function get_header(){
		$base_url = $this->services->get('config')->get('base_url');
		
		$output =
			'<!DOCTYPE html>' . "\n" .
			'<html>' . "\n" .
			'<head>' . "\n" .
			'<title>' . $this->services->get('config')->get('title') . '</title>' . "\n" .
			'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>' . "\n" .
			'<link rel="stylesheet" type="text/css" href="' . $base_url . 'styles/dictionary.css"/>' . "\n" .
			'<script type="text/javascript" src="' . $base_url . 'scripts/DOM_extension.js"></script>' . "\n" .
			'<script type="text/javascript" src="' . $base_url . 'scripts/common.js"></script>' . "\n" .
			'<script type="text/javascript" src="' . $base_url . 'scripts/construction.js"></script>' . "\n" .
			'<script type="text/javascript" src="' . $base_url . 'scripts/editing.js"></script>' . "\n" .
			'<script>' . "\n" .
			'configuration.parameters = {' . "\n" .
			'	\'base_url\':\'' . $base_url . '\'' . "\n" .
			'}' . "\n" .
			'localization.texts = {' . "\n" .
				$this->get_translations([
					//'no results found',
					'entry not found',
					'create a new one?',
					'create',
					'login',
					'password',
					'log in',
					'cancel',
					'incorrect credentials',
					'log out',
					'delete',
					'up',
					'down',
					'edit',
					'add category label',
					'add form',
					'add context',
					'add translation',
					'add phrase',
					'add sense',
				]) .
			'}' . "\n" .
			'</script>' . "\n" .
			'</head>' . "\n" .
			'<body>' . "\n";
		return $output;
	}
	
	private function get_translations(array $labels){
		$output = '';
		
		foreach($labels as $label){
			$output .= $this->get_translation($label);
		}
		
		return $output;
	}
	
	private function get_translation($label){
		$output = "	'$label':'{$this->localization->get_text($label)}', \n";
		return $output;
	}
	
	//------------------------------------------------------------------------
	// editor toolbar template
	//------------------------------------------------------------------------
	
	private function get_editor_toolbar(){
		$output = '';
		
		$output .=
			'<div id="toolbar" class="toolbar">' . "\n" .
			'<div id="editor_toolbar" class="editor_toolbar">' . "\n";
		
		if($this->data['editor']){
			$output .=
				'<div class="editor">' .
					$this->data['editor'] .
				'</div>' . "\n" .
				'<button class="button" onclick="logEditorOut(function(){showEditorLogIn(); location.reload()})">' .
					$this->localization->get_text('log out') .
				'</button>' . "\n";
		} else {
			$output .=
				'<div class="editor_log_in" onclick="showEditorCredentialsInput()">' .
					$this->localization->get_text('log in') .
				'</div>' . "\n";
		}
		
		$output .=
			'</div>' . "\n" .  // .editor_toolbar
			'</div>' . "\n";   // .toolbar
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// title template
	//------------------------------------------------------------------------
	
	private function get_title(){
		$output =
			'<div class="title">' .
				'<h1>' .
					$this->services->get('config')->get('title') .
				'</h1>' .
			'</div>';
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// mode toolbar template
	//------------------------------------------------------------------------
	
	private function get_mode_toolbar(){
		$output = '';
		
		$output .=
			'<div class="edition_toolbar">' . "\n";
		
		if($this->data['mode'] == 'edition'){
			$output .=
				'<a class="mode_switch" href="?h=' . $this->data['headword'] . '">' .
					$this->localization->get_text('view') .
				'</a>' . "\n";
		} else {
			$output .=
				'<a class="mode_switch" href="?h=' . $this->data['headword'] . '&m=edition">' .
					$this->localization->get_text('edition') .
				'</a>' . "\n";
		}
		
		$output .=
			'</div>' . "\n";  // .edition_toolbar
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// search panel template
	//------------------------------------------------------------------------
	
	private function get_search_panel(){
		return (new Search_View($this->services, $this->data))->render();
	}
	
	//------------------------------------------------------------------------
	// template matching selected headword
	//------------------------------------------------------------------------
	
	private function get_entries(){
		
		switch($this->data['mode']){
			case 'edition' :
				$layout = new Edition_Layout($this->localization);
				break;
			case 'view' :
				$layout = new View_Layout($this->localization);
				break;
		}
		
		$output = '';
		
		$output .=
			'<div id="content_panel">' . "\n";
		
		foreach($this->data['entries'] as $entry){
			$output .= $layout->parse($entry);
		}
		
		$output .=
			'</div>' . "\n";
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// page of entry not found
	//------------------------------------------------------------------------
	
	private function get_entry_not_found(){
		$output = '';
		
		$output .=
			'<div id="content_panel">' . "\n";
		
		if($this->data['mode'] == 'edition'){
			$output .=
				'<div>' .
					$this->localization->get_text('entry not found', [
						'headword' => '<b>' . $this->data['headword'] . '</b>'
					]) .
					$this->localization->get_text('create a new one?') .
				'</div>' . "\n" .
				'<button onclick="Entry.add(\'' . $this->data['headword'] . '\')">' .
					$this->localization->get_text('create') .
				'</button>' . "\n";
		} else {
			$output .=
				'<div>' .
					$this->localization->get_text('entry not found', [
						'headword' => '<b>' . $this->data['headword'] . '</b>'
					]) .
				'</div>' . "\n";
		}
		
		$output .=
			'</div>' . "\n";
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// HTML footer
	//------------------------------------------------------------------------
	
	private function get_footer(){
		$output =
			'</body>' . "\n" .
			'</html>' . "\n";
		return $output;
	}
}


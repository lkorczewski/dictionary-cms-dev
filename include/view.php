<?php

namespace DCMS;

require_once 'include/localization.php';
require_once 'include/edition_layout.php';
require_once 'include/view_layout.php';

class View {
	private $data;
	private $localization;
	
	//------------------------------------------------------------------------
	
	public function __construct($data){
		$this->data = $data;
		$this->localization = new \Localization();
		$this->localization->set_path($this->data['config']['locale_path']);
		$this->localization->set_locale($this->data['config']['locale']);
	}
	
	//------------------------------------------------------------------------
	// displaying layout with static page
	//------------------------------------------------------------------------
	
	public function show_home_page(){
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
	
	public function show_entries(){
		
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
		$output =
			'<!DOCTYPE html>' . "\n" .
			'<html>' . "\n" .
			'<head>' . "\n" .
			'<title>' . $this->data['config']['title'] . '</title>' . "\n" .
			'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>' . "\n" .
			'<link rel="stylesheet" type="text/css" href="styles/dictionary.css"/>' . "\n" .
			'<script type="text/javascript" src="scripts/DOM_extension.js"></script>' . "\n" .
			'<script type="text/javascript" src="scripts/common.js"></script>' . "\n" .
			'<script type="text/javascript" src="scripts/construction.js"></script>' . "\n" .
			'<script type="text/javascript" src="scripts/editing.js"></script>' . "\n" .
			'<script>' . "\n" .
			'localization.texts = {' . "\n" .
				$this->get_translation('no results found') .
				$this->get_translation('login') .
				$this->get_translation('password') .
				$this->get_translation('log in') .
				$this->get_translation('cancel') .
				$this->get_translation('incorrect credentials') .
				$this->get_translation('log out') .
				$this->get_translation('delete') .
				$this->get_translation('up') .
				$this->get_translation('down') .
				$this->get_translation('edit') .
			'}' . "\n" .
			'</script>' . "\n" .
			'</head>' . "\n" .
			'<body>' . "\n";
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
			'</div>' . "\n" .  // editor_toolbar
			'</div>' . "\n";   // toolbar
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// title template
	//------------------------------------------------------------------------
	
	private function get_title(){
		$output =
			'<div class="title">' .
				'<h1>' .
					$this->data['config']['title'] .
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
			'</div>' . "\n";
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// search panel template
	//------------------------------------------------------------------------
	
	private function get_search_panel(){
		$output = '';
		
		$output .=
			'<div id="search_panel">' . "\n" .
				'<form id="search_form" onsubmit="event.preventDefault(); searchHeadwordsLike(document.getElementById(\'search_mask_input\').value)">' . "\n" .
					'<span id="search_mask_input_wrapper">' .
						'<input id="search_mask_input" type="text" value="' . $this->data['search_mask'] . '"></input>' .
					'</span>' . "\n" .
					'<button id="search_button" type="submit" class="button search">' .
						$this->localization->get_text('search') .
					'</button>' . "\n" .
				'</form>' . "\n" .
				'<div id="search_results_container">' . "\n";
				
		if(count($this->data['search_results']) == 0){
			$output .=
				'<div class="search_message">' .
					$this->localization->get_text('no results found') .
				'</div>';
		} else {
			foreach($this->data['search_results'] as $search_result){
				$output .=
					'<div class="search_result">' .
						'<a href="' . '?h=' . $search_result . '">' .
							$search_result .
						'</a>' .
					'</div>' . "\n";
			}
		}
				
		$output .=
				'</div>' . "\n" .
			'</div>' . "\n";
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	// template matching selected headword
	//------------------------------------------------------------------------
	
	private function get_entries(){
		
		switch($this->data['mode']){
			case 'edition' :
				$layout = new \Edition_Layout($this->localization);
				break;
			case 'view' :
				$layout = new \View_Layout($this->localization);
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
				'Entry <b>' . $this->data['headword'] . '</b> doesn\'t exist. Create a new one?' .
				'</div>' . "\n" .
				'<button onclick="addEntry(\'' . $this->data['headword'] . '\')">' .
				'create' .
				'</button>' . "\n";
		} else {
			$output .=
				'<div>' .
				'Entry <b>' . $this->data['headword'] . '</b> doesn\'t exist.' .
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

?>

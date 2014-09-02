<?php

namespace DCMS\Views;

require_once 'include/localization.php';

use DCMS\Localization;

class Search_View {
	
	protected $data;
	protected $localization;
	
	function __construct($data, Localization $localization){
		$this->data          = $data;
		$this->localization  = $localization;
	}
	
	function render(){
		return $this->get_search_panel();
	}
	
	//------------------------------------------------------------------------
	// search panel template
	//------------------------------------------------------------------------
	
	private function get_search_panel(){
		$output = '';
		
		$output .=
			'<div id="search_panel">' . "\n" .
			$this->get_search_bar() .
			'<div id="search_results_container">' . "\n";
		
		if(count($this->data['search_results']) == 0){
			$output .= $this->get_search_results_not_found();
		} else {
			$output .= $this->get_search_results();
		}
		
		$output .=
			'</div>' . "\n" . // .search_results_container
			'</div>' . "\n"; // .search_panel
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	
	private function get_search_bar(){
		$output = '';
		
		$output .=
			'<form id="search_form" onsubmit="event.preventDefault(); searchHeadwordsLike(document.getElementById(\'search_mask_input\').value)">' . "\n" .
			'<span id="search_mask_input_wrapper">' .
			'<input id="search_mask_input" type="text" value="' . $this->data['search_mask'] . '"></input>' .
			'</span>' . "\n" .
			'<button id="search_button" type="submit" class="button search">' .
			$this->localization->get_text('search') .
			'</button>' . "\n" .
			'</form>' . "\n";
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	
	private function get_search_results_not_found(){
		$output = '';
		
		$output .=
			'<div class="search_message">' . "\n";
		
		if($this->data['mode'] == 'edition'){
			$output .=
				'<div>' .
				str_replace('{{1}}', '<b>' . $this->data['search_mask'] . '</b>' , $this->localization->get_text('entry not found')) .
				/*$this->localization->get_text('no results found') .*/
				$this->localization->get_text('create a new one?') .
				'</div>' . "\n" .
				'<div>' .
				'<button class="button create" onclick="addEntry(document.getElementById(\'search_mask_input\').value)">' .
				$this->localization->get_text('create') .
				'</button>' .
				'</div>' . "\n";
		} else {
			$output .=
				str_replace('{{1}}', '<b>' . $this->data['search_mask'] . '</b>' , $this->localization->get_text('entry not found'));
		}
		
		$output .=
			'</div>' . "\n"; // .search_message
		
		return $output;
	}
	
	//------------------------------------------------------------------------
	
	private function get_search_results(){
		$output = '';
		
		$mode_parameter = '';
		if($this->data['mode'] == 'edition')
			$mode_parameter = '&m=edition';
		
		foreach($this->data['search_results'] as $search_result){
			$output .=
				'<div class="search_result">' .
				'<a href="' . '?h=' . $search_result . $mode_parameter . '">' .
				$search_result .
				'</a>' .
				'</div>' . "\n";
		}
		
		return $output;
	}
	
}

<?php

namespace DCMS\Controllers;

use Dictionary\Dictionary;

class Entry_Controller {
	
	protected $dictionary;
	
	function __construct(Dictionary $dictionary){
		$this->dictionary = $dictionary;
	}
	
	function execute(){
		$headword = isset($_GET['h']) ? $_GET['h'] : '';
		
		$entries = $headword ? $this->dictionary->get_entries_by_headword($headword) : [];
		
		return [
			'headword' => $headword,
			'entries'  => $entries,
		];
	}
	
}

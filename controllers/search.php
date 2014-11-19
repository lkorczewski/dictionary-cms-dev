<?php

namespace DCMS\Controllers;

use Dictionary\Dictionary;
use Config\Config;

class Search_Controller {
	
	protected $dictionary;
	protected $config;
	
	function __construct(Dictionary $dictionary, Config $config){
		$this->dictionary  = $dictionary;
		$this->config      = $config;
	}
	
	function execute(){
		$search_mask     = isset($_SESSION['search_mask'])     ? $_SESSION['search_mask']     : '';
		$search_results  = isset($_SESSION['search_results'])  ? $_SESSION['search_results']  : false;
		
		if($search_results == false){
			$search_results = $this->dictionary->get_headwords($search_mask, $this->config->get('search_results_limit'));
			$_SESSION['search_results'] = $search_results;
		}
		
		return [
			'search_mask'     => $search_mask,
			'search_results'  => $search_results,
		];
	}
	
}


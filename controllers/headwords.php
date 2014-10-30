<?php

namespace Controllers;

use Core\Service_Container;

class Headwords {
	
	protected $services;
	
	function __construct(Service_Container $services){
		$this->services = $services;
	}
	
	function search($headword_mask = ''){
		$data          = $this->services->get('data');
		$config        = $this->services->get('config');
		$session       = $this->services->get('session');
		$json_response = $this->services->get('json_response');
		
		$session->set('search_mask', $headword_mask);
		$headwords = $data->get_headwords(
			$headword_mask,
			$config->get('search_results_limit')
		);
		$session->set('search_results', $headwords);
		
		header('Content-Type: application/json');	
		echo JSON_encode($headwords, JSON_UNESCAPED_UNICODE);
	}
	
}


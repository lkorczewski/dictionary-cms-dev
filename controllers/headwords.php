<?php

namespace Controllers;

class Headwords extends Abstracts\Controller {
	
	function search($headword_mask = ''){
		$data          = $this->services->get('data');
		$config        = $this->services->get('config');
		$session       = $this->services->get('session');
		//$json_response = $this->services->get('json_response');
		
		$headwords = $data->get_headwords(
			$headword_mask,
			$config->get('search_results_limit')
		);
		
		$session->set('search_mask', $headword_mask);
		$session->set('search_results', $headwords);
		
		// todo: use json_response
		header('Content-Type: application/json');
		echo JSON_encode($headwords, JSON_UNESCAPED_UNICODE);
	}
	
}


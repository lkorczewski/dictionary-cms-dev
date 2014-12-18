<?php

namespace Controllers;

use DCMS\JSON_Response;

class Category_Labels extends Abstracts\Controller {
	
	function list_all(){
		
		/** @var \DCMS\JSON_Response $json_response */
		$json_response = $this->services->get('json_response');
		
		/** @var \Dictionary\Data $data */
		$data = $this->services->get('data');
		
		$category_labels = $data->access('category_label')->list_all();
		
		if($category_labels === false){
			$json_response->fail(JSON_Response::MESSAGE_QUERY_FAILURE);
		}
		
		// todo: use json_response
		header('Content-Type: application/json');
		echo JSON_encode($category_labels, JSON_UNESCAPED_UNICODE);
	}
	
}


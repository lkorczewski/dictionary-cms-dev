<?php

namespace DCMS;

use Config\Config_Interface;

class Request {
	
	protected $config;
	
	function __construct(Config_Interface $config){
		$this->config = $config;
	}
	
	function get_parameter($parameter, $default = null){
		
		if(isset($_POST[$parameter])){
			return $_POST[$parameter];
		}
		
		if($this->config->get('debug') && isset($_GET[$parameter])){
			return $_GET[$parameter];
		}
		
		return $default;
	}
	
}


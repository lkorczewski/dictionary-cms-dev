<?php

namespace Controllers\Abstracts;

use Core\Service_Container;

abstract class Controller {
	
	protected $services;
	
	function __construct(Service_Container $services){
		$this->services = $services;
	}
	
}


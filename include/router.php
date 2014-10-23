<?php

namespace DCMS;

use ReflectionMethod;

// todo: compiling all paths / lazy compiling single path

class Router {
	
	protected $routes;
	
	function __construct(array $routes){
		$this->routes = $routes;
	}
	
	function route($path){
		foreach($this->routes as $pattern => $action){
			if($this->test_path($path, $pattern, $matches)){
				$this->execute_action($action, $matches);
				break;
			}
		}
	}
	
	protected function test_path($path, $pattern, &$matches){
		$regex = $this->compile_pattern_to_regex($pattern);
		$result = preg_match($regex, $path, $matches);
		
		return $result;
	}
	
	protected function compile_pattern_to_regex($path){
		$regex = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/,]*)', $path);
		$regex = '`^' . $regex . '$`';
		
		return $regex;
	}
	
	function execute_action($action, array $parameters = []){
		list($controller, $action) = explode(':', $action);
		$controller_object = new $controller;
		$method_reflection = new ReflectionMethod($controller_object, $action);
		
		$method_parameters = [];
		foreach($method_reflection->getParameters() as $parameter_reflection){
			if(isset($parameters[$parameter_reflection->getName()])){
				$method_parameter = $parameters[$parameter_reflection->getName()];
			} else if ($parameter_reflection->isDefaultValueAvailable()){
				$method_parameter = $parameter_reflection->getDefaultValue();
			} else break;
			
			$method_parameters[] = $method_parameter;
		}
		
		$method_reflection->invokeArgs($controller_object, $method_parameters);
		//call_user_func_array([$controller, $action], $parameters);
	}
	
}


<?php

namespace DCMS;

use ReflectionMethod;

// todo: compiling all paths / lazy compiling single path

class Router {
	
	protected $routes;
	protected $controller_generator;
	
	//----------------------------------------------------------------
	// constructing
	//----------------------------------------------------------------
	
	function __construct(array $routes, Callable $controller_generator = null){
		$this->routes                = $routes;
		$this->controller_generator  = $controller_generator;
	}
	
	//----------------------------------------------------------------
	// routing by request URL
	//----------------------------------------------------------------
	
	function route(){
		return $this->route_path($_SERVER['PATH_INFO']);
	}
	
	//----------------------------------------------------------------
	// routing selected path
	//----------------------------------------------------------------	
	
	function route_path($path){
		$path = $this->normalize_path($path);
		
		foreach($this->routes as $pattern => $action){
			if($this->test_path($path, $pattern, $matches)){
				$result = $this->execute_action($action, $matches);
				return $result;
			}
		}
		
		return false;
	}
	
	//----------------------------------------------------------------
	// normalizing path
	//----------------------------------------------------------------
	
	protected function normalize_path($path){
		return trim($path, '/');
	}
	
	//----------------------------------------------------------------	
	// testing if path matches selected pattern
	//----------------------------------------------------------------	
	
	protected function test_path($path, $pattern, &$matches){
		$regex = $this->compile_pattern_to_regex($pattern);
		$result = preg_match($regex, $path, $matches);
		
		return $result;
	}
	
	//----------------------------------------------------------------	
	// compiling path pattern to  regex
	//----------------------------------------------------------------
		
	protected function compile_pattern_to_regex($path){
		$regex = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/,]*)', $path);
		$regex = '`^' . $regex . '$`';
		
		return $regex;
	}
	
	//----------------------------------------------------------------
	// executing action string
	//----------------------------------------------------------------
	
	function execute_action($action, array $parameters = []){
		list($controller, $action) = explode(':', $action);
		
		$controller_object = $this->instantiate_controller($controller);
		$method_reflection = new ReflectionMethod($controller_object, $action);
		
		$method_parameters = $this->get_method_parameters($method_reflection, $parameters);
		
		$result = $method_reflection->invokeArgs($controller_object, $method_parameters);
		
		return $result;
	}
	
	//----------------------------------------------------------------
	// instantiating controller
	//----------------------------------------------------------------
	protected function instantiate_controller($controller){
		if($this->controller_generator){
			$controller_generator = $this->controller_generator;
			$controller_object = $controller_generator($controller);
		} else {
			$controller_object = new $controller();
		}
		
		return $controller_object;
	}
	
	//----------------------------------------------------------------
	// selecting named parameters matching the action method
	//----------------------------------------------------------------
	
	protected function get_method_parameters(
		ReflectionMethod  $method_reflection,
		array             $parameters
	){
		
		$method_parameters = [];
		
		foreach($method_reflection->getParameters() as $parameter_reflection){
			
			if(isset($parameters[$parameter_reflection->getName()])){
				$method_parameter = $parameters[$parameter_reflection->getName()];
			} else if ($parameter_reflection->isDefaultValueAvailable()){
				$method_parameter = $parameter_reflection->getDefaultValue();
			} else break;
			
			$method_parameters[] = $method_parameter;
		}
		
		return $method_parameters;
	}
	
}


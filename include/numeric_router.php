<?php

namespace DCMS;

// todo: compiling all paths / lazy compiling single path

class Numeric_Router {
	
	protected $routes;
	
	function __construct(array $routes){
		$this->routes = $routes;
	}
	
	//----------------------------------------------------------------
	// routing selected path (legacy method)
	//----------------------------------------------------------------	
	
	function route($path){
		foreach($this->routes as $pattern => $action){
			if($this->test_path($path, $pattern, $matches)){
				$result = $this->execute_action($action, $matches);
				return $result;
			}
		}
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
		$regex = preg_replace('/\{([a-z_]+)\}/', '([^/,]*)', $path);
		$regex = '`^' . $regex . '$`';
		
		return $regex;
	}
	
	//----------------------------------------------------------------
	// executing action string basing on order of parameters
	//----------------------------------------------------------------
	
	function execute_action($action, array $parameters = []){
		list($controller, $action) = explode(':', $action);
		
		$numeric_parameters = $this->get_numeric_parameters($parameters);
		
		$result = call_user_func_array([$controller, $action], $numeric_parameters);
		
		return $result;
	}
	
	//----------------------------------------------------------------
	// selecting numeric parameters
	//----------------------------------------------------------------
	
	protected function get_numeric_parameters(array $parameters){
		$numeric_parameters = [];
		foreach($parameters as $key => $parameter){
			if(is_numeric($key) && $key != 0){
				$numeric_parameters[] = $parameters[$key];
			}
		}
		
		return $numeric_parameters;
	}
	
}


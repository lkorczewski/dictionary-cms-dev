<?php

namespace DCMS;

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
			}
		}
	}
	
	protected function test_path($path, $pattern, &$matches){
		$regex = $this->compile_pattern_to_regex($pattern);
		$result = preg_match($regex, $path, $matches);
		
		return $result;
	}
	
	protected function compile_pattern_to_regex($path){
		$regex = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/]*)', $path);
		$regex = '`^' . $regex . '$`';
		
		return $regex;
	}
	
	function execute_action($action, array $parameters = []){
		list($controller, $action) = explode(':', $action);
		call_user_func_array([$controller, $action], $parameters);
	}
	
}


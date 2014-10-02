<?php

namespace DCMS;

// to consider:
//  - lazy loading?

class Session {
	
	function __construct(array $parameters = null){
		
		if(isset($parameters['name'])){
			session_name($parameters['name']);
		}
		
		if(isset($parameters['lifetime'])
			|| isset($parameters['path'])
			|| isset($parameters['domain'])
		){
			$lifetime  = isset($parameters['lifetime'])  ? $parameters['lifetime']  : 0;
			$path      = isset($parameters['path'])      ? $parameters['path']      : '/';
			$domain    = isset($parameters['domain'])    ? $parameters['domain']    : '';
			
			session_set_cookie_params($lifetime, $path, $domain);
		}
		
		session_start();
	}
	
	/*
	function started(){
		if(session_status() === PHP_SESSION_ACTIVE){
			return true;
		}
		
		return false;
	}
	*/
	
	function has($key){
		return isset($_SESSION[$key]);
	}
	
	function set($key, $value){
		$_SESSION[$key] = $value;
	}
	
	function get($key, $default = null){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		
		return $default;
	}
	
	function delete($key){
		unset($_SESSION[$key]);
	}
}


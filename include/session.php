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
	
	//----------------------------------------------------------------
	
	function set($key, $value){
		$_SESSION[$key] = $value;
		
		return $this;
	}
	
	function __set($key, $value){
		$this->set($key, $value);
	}
	
	//----------------------------------------------------------------
	
	function has($key){
		return isset($_SESSION[$key]);
	}
	
	function __isset($key){
		return $this->has($key);
	}
	
	//----------------------------------------------------------------
	
	function get($key, $default = null){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		
		return $default;
	}
	
	function __get($key){
		return $this->get($key);
	}
	
	//----------------------------------------------------------------
	
	function delete($key){
		unset($_SESSION[$key]);
	}
	
	function __unset($key){
		$this->delete($key);
	}
	
	//----------------------------------------------------------------
	
	function reset(){
		$_SESSION = [];
	}
	
	//----------------------------------------------------------------
	
	function destroy(){
		session_destroy();
	}
}


<?php

//namespace DCMS;

use Database\Database;

class Script {
	private static $config = '';
	private static $root_path;
	private static $debug_enabled = false;
	
	//--------------------------------------------------------------------
	// setting path for different 
	//--------------------------------------------------------------------
	
	static function set_root_path($root_path){
		self::$root_path = $root_path;
		self::add_include_path($root_path);
	}
	
	//--------------------------------------------------------------------
	// loading config
	//--------------------------------------------------------------------
	
	static function load_config(){
		
		// loading config
		$config = [];
		require (self::$root_path ? self::$root_path . '/' : '') . 'config.php';
		self::$config = $config;
		
		// implementing configuration
		
		// debug
		
		if(isset($config['debug']) && $config['debug'] == true){
			self::$debug_enabled = true;
			ini_set('display_errors',   1);
			ini_set('error_reporting',  E_ALL);
		}
		
		// include path
		// TO DO: absolute paths
		
		if(isset($config['include_path'])){
			$include_path = (self::$root_path ? self::$root_path . '/' : '') . $config['include_path'];
			self::add_include_path($include_path);
		}
		
		return $config;
	}
	
	static function add_include_path($path){
		set_include_path(get_include_path() . PATH_SEPARATOR . realpath($path));
	}
	
	//--------------------------------------------------------------------
	// start session
	//--------------------------------------------------------------------
	
	static function start_session(){
		
		if(isset(self::$config['session_name'])){
			session_name(self::$config['session_name']);
		}
		
		if(isset(self::$config['session_domain']) || isset(self::$config['session_path'])){
			$session_domain = isset(self::$config['session_domain'])
				? self::$config['session_domain'] : '';
			$session_path = isset(self::$config['session_path'])
				? self::$config['session_path'] : '/';
			
			session_set_cookie_params(0, $session_path, $session_domain);
		}
		
		session_start();
	}
	
	//--------------------------------------------------------------------
	// connecting to database
	//--------------------------------------------------------------------
	
	static function connect_to_database(){
		require_once 'database/database.php';
		
		$database_config = [];
		if(isset(self::$config['db_host'])){
			$database_config['host'] = self::$config['db_host'];
		}
		if(isset(self::$config['db_port'])){
			$database_config['port'] = self::$config['db_port'];
		}
		if(isset(self::$config['db_user'])){
			$database_config['user'] = self::$config['db_user'];
			if(isset(self::$config['db_password'])){
				$database_config['password'] = self::$config['db_password'];
			}
		}
		if(isset(self::$config['db_database'])){
			$database_config['database'] = self::$config['db_database'];
		}
		// TODO: check if eager connecting may be skipped
		$database = new Database($database_config);
		$database->connect();
		
		return $database;
	}
	
	//--------------------------------------------------------------------
	// acquiring value from parameter
	//--------------------------------------------------------------------
	
	static function get_parameter($parameter, $default = false){
		$value = $default;
		
		if(isset($_POST[$parameter])){
			$value = $_POST[$parameter];
		} else {
			if(self::$debug_enabled && isset($_GET[$parameter])){
				$value = $_GET[$parameter];
			}
		}
		
		return $value;
	}
	
	//--------------------------------------------------------------------
	// returning failure
	//--------------------------------------------------------------------
	
	static function fail($message){
		$output =
			'{' .
			'"status":"failure"' .
			',' .
			'"message":"' . $message . '"' .
			'}';
		exit($output);
	}
	
	//--------------------------------------------------------------------
	// returning success
	//--------------------------------------------------------------------
	
	static function succeed(array $results = null){
		$output = '';
		
		$output .= '{';
		$output .= '"status":"success"';
		
		if(is_array($results)){
			foreach($results as $result_key => $result_value){
				$output .= ',"' . $result_key . '":"' . $result_value . '"';
			}
		}
		
		$output .= '}';
		
		exit($output);
	}
	
}


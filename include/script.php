<?php

//namespace DCMS;

class Script {
	private static $config = '';
	private static $root_path;
	private static $debug_enabled = false;
	
	//--------------------------------------------------------------------
	// setting path for different 
	//--------------------------------------------------------------------
	
	static function set_root_path($root_path){
		self::$root_path = $root_path;
	}
	
	//--------------------------------------------------------------------
	// loading config
	//--------------------------------------------------------------------
	
	static function load_config(){
		
		// loading config
		$config = array();
		require_once (self::$root_path ? self::$root_path . '/' : '') . 'config.php';
		self::$config = $config;
		
		// implementig configuration
		
		// debug
		
		if(isset($config['debug']) && $config['debug'] == true){
			self::$debug_enabled = true;
			ini_set('display_errors',1);
			ini_set('error_reporting', E_ALL);
		}
		
		// include path
		// TO DO: absolute paths
		
		if(isset($config['include_path'])){
			set_include_path(
				get_include_path() .
				PATH_SEPARATOR .
				(self::$root_path ? self::$root_path . '/' : '') .
				$config['include_path']
			);
		}
		
		return $config;
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
		
		$database = new Database();
		if(isset(self::$config['db_host'])) $database->set_host(self::$config['db_host']);
		if(isset(self::$config['db_port'])) $database->set_port(self::$config['db_port']);
		if(isset(self::$config['db_user'])) $database->set_user(self::$config['db_user'],
			isset(self::$config['db_password'])?self::$config['db_password']:''
		);
		if(isset(self::$config['db_database'])) $database->set_database(self::$config['db_database']);
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
		die($output);
	}
	
}

?>

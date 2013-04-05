<?php

class Script {
	private static $config = '';
	private static $root_path;
	
	static function set_root_path($root_path){
		self::$root_path = $root_path;
	}
	
	static function load_config(){
		
		// loading config
		$config = array();
		require_once (self::$root_path ? self::$root_path . '/' : '') . 'config.php';
		self::$config = $config;
		
		// implementig configuration
		
		// debug
		
		if(isset($config['debug']) && $config['debug'] == true){
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
	
}

?>

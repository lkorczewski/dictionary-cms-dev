<?php

use Core\Service_Container;
use Config\Config;
use Database\Database;
use Dictionary\MySQL_Data;
use Dictionary\Dictionary;

use DCMS\Request;
use DCMS\Session;
use DCMS\Data as DCMS_Data;
use DCMS\JSON_Response;

return new Service_Container([
	
	'config' => function(){
			require __DIR__ . '/config.php';
			return new Config($config);
		},
	
	'database' => function(Service_Container $services){
		
		/** @var \Config\Config_Interface $config */
		$config = $services->get('config');
		
		$parameters = [];
		if($config->has('db_host')){
			$parameters['host'] = $config->get('db_host');
		}
		if($config->has('db_port')){
			$parameters['port'] = $config->get('db_port');
		}
		if($config->has('db_user')){
			$parameters['user'] = $config->get('db_user');
		}
		if($config->has('db_password')){
			$parameters['password'] = $config->get('db_password');
		}
		if($config->has('db_database')){
			$parameters['database'] = $config->get('db_database');
		}
		
		return new Database($parameters);
	},
	
	'data' => function(Service_Container $services){
		require_once 'dictionary/data/mysql_data.php';
		return new MySQL_Data($services->get('database'));
	},
	
	'dictionary' => function(Service_Container $services){
		return new Dictionary($services->get('data'));
	},
	
	'dcms_data' => function(Service_Container $services){
		require_once __DIR__ . '/include/data.php';
		return new DCMS_Data($services->get('database'));
	},
	
	'request' => function(Service_Container $services){
		require_once __DIR__ . '/include/request.php';
		return new Request($services->get('config'));
	},
	
	'session' => function(Service_Container $services){
		
		/** @var \Config\Config_Interface $config */
		$config = $services->get('config');
		
		$parameters = [];
		if($config->has('session_name')){
			$parameters['name'] = $config->get('session_name');
		}
		if($config->has('session_domain')){
			$parameters['domain'] = $config->get('session_domain');
		}
		if($config->has('session_path')){
			$parameters['path'] = $config->get('session_path');
		}
		
		require_once __DIR__ . '/include/session.php';
		return new Session($parameters);
	},

	'localization' => function(Service_Container $services){
		require_once __DIR__ . '/include/localization.php';
		return new DCMS\Localization(
			$services->get('config')->get('locale_path'),
			$services->get('config')->get('locale')
		);
	},

	'json_response' => function(){
		require_once __DIR__ . '/include/json_response.php';
		return new JSON_Response();
	},
	
]);


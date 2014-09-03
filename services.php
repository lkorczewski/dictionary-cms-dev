<?php

use Core\Service_Container;
use Database\Database;
use Dictionary\MySQL_Data;
use Dictionary\Dictionary;

return new Service_Container([
	
	'config' => function(){
			require __DIR__ . '/config.php';
			return new \Config\Config($config);
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
		return new MySQL_Data($services->get('database'));
	},
	
	'dictionary' => function(Service_Container $services){
		return new Dictionary($services->get('data'));
	},
	
]);


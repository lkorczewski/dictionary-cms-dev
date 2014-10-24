<?php

require_once __DIR__ . '/router.php';

$routes = [
	'controller/action'              => 'Book:show',
	'controller/action/{id}'         => 'Book:show',
	'controller/action/{id}/{param}' => 'Book:show',
	'controller/action/{id},{param}' => 'Book:show',
];

class Book {
	function show(
		$id      = null,
		$param   = null,
		$param2  = null,
		$param3  = null
	){
		
		if(isset($id)){
			echo "id: $id";
		}
		
		if(isset($param)){
			echo " param: $param";
		}
		
		if(isset($param2)){
			echo " param2: $param2";
		}
		
		if(isset($param3)){
			echo " param3: $param3";
		}
		
		echo "\n";
	}
}

$paths = [
	'wrong/path',
	'controller/action',
	'controller/action/4',
	'controller/action/5/parameter',
	'controller/action/6,parameter',
];

$router = new DCMS\Router($routes);
foreach($paths as $path){
	$router->route($path);
	$router->route_legacy($path);
}


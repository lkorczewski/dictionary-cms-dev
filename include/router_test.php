<?php

require_once __DIR__ . '/router.php';
require_once __DIR__ . '/numeric_router.php';

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
		/*
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
		*/
		
		return true;
	}
}

$paths = [
	'wrong/path',
	'controller/action',
	'controller/action/4',
	'controller/action/5/parameter',
	'controller/action/6,parameter',
];

// testing new version

$t = microtime(true);

for($i=0; $i<1000; $i++){
	$router = new DCMS\Router($routes);
	foreach($paths as $path){
		$router->route($path);
	}
}

$t = microtime(true) - $t;

echo "named parameters router: $t\n";

// testing old version

$t = microtime(true);

for($i=0; $i<1000; $i++){
	$router = new DCMS\Numeric_Router($routes);
	foreach($paths as $path){
		$router->route($path);
	}
}

$t = microtime(true) - $t;

echo "numeric parameters router: $t\n";

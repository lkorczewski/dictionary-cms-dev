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

$router = new DCMS\Router($routes);
$router->route('wrong/path');
$router->route('controller/action');
$router->route('controller/action/4');
$router->route('controller/action/5/parameter');
$router->route('controller/action/6,parameter');


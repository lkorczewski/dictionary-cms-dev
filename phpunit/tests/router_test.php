<?php

require_once __DIR__ . '/../../include/router.php';

use DCMS\Router;

class Test_Controller {
	
	function test_method(
		$id     = null,
		$format = null,
		$mode   = null
	){
		return [
			'id'      => $id,
			'format'  => $format,
			'mode'    => $mode,
		];
	}
}

class Router_Test extends PHPUnit_Framework_TestCase {
	
	protected function get_router(){
		
		return new Router([
			'wrong/path'                 => 'Test_Controller:test_method',
			'book/show'                  => 'Test_Controller:test_method',
			'book/show/{id}'             => 'Test_Controller:test_method',
			'book/show/{id}/{mode}'      => 'Test_Controller:test_method',
			'book/show/{id},{format}'    => 'Test_Controller:test_method',
		]);
	}
	
	function test_routing_by_path(){
		
		$router = $this->get_router();
		
		$this->assertEquals($router->route_path('book/show'), [
			'id'     => null,
			'format' => null,
			'mode'   => null,
		]);
		
		$this->assertEquals($router->route_path('book/show/4'), [
			'id'     => 4,
			'format' => null,
			'mode'   => null,
		]);
		
		$this->assertEquals($router->route_path('book/show/5/edit'), [
			'id'     => 5,
			'format' => null,
			'mode'   => 'edit',
		]);
		
		$this->assertEquals($router->route_path('book/show/6,short'), [
			'id'     => 6,
			'format' => 'short',
			'mode'   => null,
		]);
		
	}
	
	function test_routing_by_http(){
		
		$router = $this->get_router();
		
		$_SERVER['PATH_INFO'] = 'book/show';
		$this->assertEquals($router->route(), [
			'id'     => null,
			'format' => null,
			'mode'   => null,
		]);
		
		$_SERVER['PATH_INFO'] = 'book/show/4';	
		$this->assertEquals($router->route(), [
			'id'     => 4,
			'format' => null,
			'mode'   => null,
		]);
	
		$_SERVER['PATH_INFO'] = 'book/show/5/edit';
		$this->assertEquals($router->route(), [
			'id'     => 5,
			'format' => null,
			'mode'   => 'edit',
		]);
	
		$_SERVER['PATH_INFO'] = 'book/show/6,short';
		$this->assertEquals($router->route(), [
			'id'     => 6,
			'format' => 'short',
			'mode'   => null,
		]);
		
	}
	
}


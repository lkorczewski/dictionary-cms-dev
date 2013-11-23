<?php

class Benchmark {
	private $functions = [];
	private $running_times = [];
	
	function register($label, $function){
		$this->functions[] = [
			'label' => $label,
			'function' => $function
		];
	}
		
	function perform(){
		foreach($this->functions as $functions_element){
			$start_time = microtime(true);
			echo $start_time . "\n";
			for($i=0; $i<1000000; $i++){
				$function = $functions_element['function'];
				$function();
			}
			$running_time = microtime(true) - $start_time;
			$this->running_times[] = [
				'label'    => $functions_element['label'],
				'runtime'  => $running_time,
			];
		}
		return $this->running_times;
	}
}

//------------------------------------

$benchmark = new Benchmark();

//------------------------------------

$function_0 = function(){
	$message = 'hello world!';
};

$benchmark->register('empty', $function_0);

//------------------------------------

$function_1 = function(){
	$message = 'hello world!';
	$result = '{"status":"success","message":"' . $message . '"}';
};

$benchmark->register('text', $function_1);

//------------------------------------

$function_2 = function(){
	$message = 'hello world!';
	$array = [
		'status' => 'success',
		'message' => $message
	];
	$result = JSON_encode($array);
};

$benchmark->register('JSON_encode', $function_2);

//------------------------------------

$running_times = $benchmark->perform();

foreach($running_times as $element){
	echo "{$element['label']}: {$element['runtime']}\n";
}

?>

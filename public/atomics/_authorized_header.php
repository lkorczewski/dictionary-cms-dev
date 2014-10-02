<?php

use \DCMS\JSON_Response;

require __DIR__ . '/../../bootstrap.php';

if(!$services->get('session')->get('editor')){
	$services->get('json_response')->fail(JSON_Response::MESSAGE_NO_AUTHORIZATION);
}


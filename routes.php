<?php

return [
	'editor/log_in'  => 'Controllers\Editor:log_in',
	'editor/log_out' => 'Controllers\Editor:log_out',
	
	'headwords'                 => 'Controllers\Headwords:search',
	'headwords/{headword_mask}' => 'Controllers\Headwords:search',
	
	'category_labels' => 'Controllers\Category_Labels:list_all', // todo: zmienić na list
];


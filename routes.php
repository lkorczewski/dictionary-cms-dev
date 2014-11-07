<?php

return [
	'category_label/list'       => 'Controllers\Category_Label:list',
	
	'headwords'                 => 'Controllers\Headwords:search',
	'headwords/{headword_mask}' => 'Controllers\Headwords:search',
	
	'editor/log_in'  => 'Controllers\Editor:log_in',
	'editor/log_out' => 'Controllers\Editor:log_out',
];


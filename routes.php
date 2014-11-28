<?php

return [
	'editor/log_in'  => 'Controllers\Editor:log_in',
	'editor/log_out' => 'Controllers\Editor:log_out',
	
	'headwords'                 => 'Controllers\Headwords:search',
	'headwords/{headword_mask}' => 'Controllers\Headwords:search',
	
	'headword/{id}/update/{value}' => 'Controllers\Headword:update',
	'headword/{id}/move_up'        => 'Controllers\Headword:move_up',
	'headword/{id}/move_down'      => 'Controllers\Headword:move_down',
	'headword/{id}/delete'         => 'Controllers\Headword:delete',
	
	'pronunciation/{id}/update/{value}' => 'Controllers\Pronunciation:update',
	'pronunciation/{id}/move_up'        => 'Controllers\Pronunciation:move_up',
	'pronunciation/{id}/move_down'      => 'Controllers\Pronunciation:move_down',
	'pronunciation/{id}/delete'         => 'Controllers\Pronunciation:delete',
	
	'category_label/{id}/update/{value}' => 'Controllers\Category_Label:update',
	'category_label/{id}/delete'         => 'Controllers\Category_Label:delete',
	'category_labels'                    => 'Controllers\Category_Labels:list_all', // todo: zmienić na list
	
	'form/{id}/update/{value}' => 'Controllers\Form:update',
	'form/{id}/move_up'        => 'Controllers\Form:move_up',
	'form/{id}/move_down'      => 'Controllers\Form:move_down',
	'form/{id}/delete'         => 'Controllers\Form:delete',
	
	'context/{id}/update/{value}' => 'Controllers\Context:update',
	'context/{id}/delete'         => 'Controllers\Context:delete',
	
	'translation/{id}/update/{value}' => 'Controllers\Translation:update',
	'translation/{id}/move_up'        => 'Controllers\Translation:move_up',
	'translation/{id}/move_down'      => 'Controllers\Translation:move_down',
	'translation/{id}/delete'         => 'Controllers\Translation:delete',
	
	'node/{node_id}/add_headword'       => 'Controllers\Node:add_headword',
	'node/{node_id}/add_pronunciation'  => 'Controllers\Node:add_pronunciation',
	'node/{node_id}/add_context'        => 'Controllers\Node:add_context',
	'node/{node_id}/add_form'           => 'Controllers\Node:add_form',
	'node/{node_id}/add_category_label' => 'Controllers\Node:add_category_label',
	'node/{node_id}/add_translation'    => 'Controllers\Node:add_translation',
	'node/{node_id}/add_phrase'         => 'Controllers\Node:add_phrase',
	'node/{node_id}/add_sense'          => 'Controllers\Node:add_sense',
];


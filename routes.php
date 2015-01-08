<?php

return [
	'editor/log_in'  => 'Controllers\Editor:log_in',
	'editor/log_out' => 'Controllers\Editor:log_out',
	
	// values
	
	'headwords'                 => 'Controllers\Headwords:search',
	'headwords/{headword_mask}' => 'Controllers\Headwords:search',
	
	'headwords/add/{node_id}' => 'Controllers\Headword:add',
	'headword/{id}/update'    => 'Controllers\Headword:update',
	'headword/{id}/move_up'   => 'Controllers\Headword:move_up',
	'headword/{id}/move_down' => 'Controllers\Headword:move_down',
	'headword/{id}/delete'    => 'Controllers\Headword:delete',
	
	'pronunciations/add/{node_id}' => 'Controllers\Pronunciation:add',
	'pronunciation/{id}/update'    => 'Controllers\Pronunciation:update',
	'pronunciation/{id}/move_up'   => 'Controllers\Pronunciation:move_up',
	'pronunciation/{id}/move_down' => 'Controllers\Pronunciation:move_down',
	'pronunciation/{id}/delete'    => 'Controllers\Pronunciation:delete',

	'category_labels'               => 'Controllers\Category_Labels:list_all', // todo: change to list
	'category_labels/add/{node_id}' => 'Controllers\Category_Label:add',
	'category_label/{id}/update'    => 'Controllers\Category_Label:update',
	'category_label/{id}/delete'    => 'Controllers\Category_Label:delete',
	
	'forms/add/{node_id}' => 'Controllers\Form:add',
	'form/{id}/update'    => 'Controllers\Form:update',
	'form/{id}/move_up'   => 'Controllers\Form:move_up',
	'form/{id}/move_down' => 'Controllers\Form:move_down',
	'form/{id}/delete'    => 'Controllers\Form:delete',
	
	'contexts/add/{node_id}' => 'Controllers\Context:add',
	'context/{id}/update'    => 'Controllers\Context:update',
	'context/{id}/delete'    => 'Controllers\Context:delete',
	
	'translations/add/{node_id}' => 'Controllers\Translation:add',
	'translation/{id}/update'    => 'Controllers\Translation:update',
	'translation/{id}/move_up'   => 'Controllers\Translation:move_up',
	'translation/{id}/move_down' => 'Controllers\Translation:move_down',
	'translation/{id}/delete'    => 'Controllers\Translation:delete',
	
	// nodes
	
	'phrases/add/{node_id}'      => 'Controllers\Phrase:add',
	'phrase/{node_id}/update'    => 'Controllers\Phrase:update',
	'phrase/{node_id}/move_up'   => 'Controllers\Phrase:move_up',
	'phrase/{node_id}/move_down' => 'Controllers\Phrase:move_down',
	'phrase/{node_id}/delete'    => 'Controllers\Phrase:delete',
	
	'senses/add/{node_id}'      => 'Controllers\Sense:add',
	'sense/{node_id}/move_up'   => 'Controllers\Sense:move_up',
	'sense/{node_id}/move_down' => 'Controllers\Sense:move_down',
	'sense/{node_id}/delete'    => 'Controllers\Sense:delete',
	
	'entries/add'            => 'Controllers\Entry:add',
	'entry/{node_id}/get'    => 'Controllers\Entry:get',
	'entry/{node_id}/delete' => 'Controllers\Entry:delete',
	
];


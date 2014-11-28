<?php

namespace Controllers;

class Node extends Abstracts\JSON_Controller {
	
	function add_headword($node_id){
		$this->init();
		$headword_id = $this->services->get('data')->access('headword')->add($node_id);
		$this->handle_query_result($headword_id, [
			'headword_id' => $headword_id,
		]);
	}
	
	function add_pronunciation($node_id){
		/*
		$xsampa = new \Phonetics\XSAMPA_Parser();
		$text = $xsampa->parse($text);
		*/
		$this->init();
		$pronunciation_id = $this->services->get('data')->access('pronunciation')->add($node_id);
		$this->handle_query_result($pronunciation_id, [
			'pronunciation_id' => $pronunciation_id,
		]);
	}
	
	function add_category_label(){
		$this->init();
		$category_label_id = $this->services->get('data')->access('category_label')->add($node_id);
		$this->handle_query_result($category_label_id, [
			'category_label_id' => $category_label_id,
		]);
	}
	
	function add_form($node_id){
		$this->init();
		$form_id = $this->services->get('data')->access('form')->add($node_id);
		$this->handle_query_result($form_id, [
			'form_id' => $form_id,
		]);
	}
	
	function add_context(){
		$this->init();
		$context_id = $this->services->get('data')->access('context')->add($node_id);
		$this->handle_query_result($context_id, [
			'context_id' => $context_id,
		]);
	}
	
	function add_translation($node_id){
		$this->init();
		$translation_id = $this->services->get('data')->access('translation')->add($node_id);
		$this->handle_query_result($translation_id, [
			'translation_id' => $translation_id,
		]);
	}
	
	function add_phrase($node_id){
		$this->init();
		$phrase_node_id = $this->services->get('data')->access('phrase')->add($node_id);
		$this->handle_query_result($phrase_node_id, [
			'node_id' => $phrase_node_id,
		]);
	}
	
	function add_sense($node_id){
		$this->init();
		$sense_access = $this->services->get('data')->access('sense');
		$sense_node_id = $sense_access->add($node_id);
		$sense_label   = $sense_access->get_label($sense_node_id);
		$this->handle_query_result($sense_node_id, [
			'node_id' => $sense_node_id,
			'label'   => $sense_label,
		]);
	}
	
}

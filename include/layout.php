<?php

require_once 'dictionary/dictionary.php';
require_once 'dictionary/entry.php';
require_once 'dictionary/sense.php';
require_once 'dictionary/translation.php';

Class Layout{
	private $output;
	private $depth;
	
	function __construct(){
		$this->output = '';
		$this->depth = 0;
	}

	function parse($entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}

	private function parse_entry(Entry $entry){
		$this->output .= '<div class="entry_container">' . "\n";
		$this->output .= '<div class="headword"><b>' . $entry->get_headword() . '</b></div>' . "\n";
		
		while($sense = $entry->get_sense()){
			$this->parse_sense($sense);
		}
		$this->output .= '<div>' . "\n";
	}
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">';
		
		$this->output .=
			'<div class="sense_label">' .
			$sense->get_label() .
			'<div class="buttons">' .
			'<button class="button move_up" onclick="move_sense_up(this.parentNode.parentNode.parentNode, ' .
			$sense->get_id() .
			')"> do góry</button>' .
			'<button class="button move_down" onclick="move_sense_down(this.parentNode.parentNode.parentNode, ' .
			$sense->get_id() .
			')"> na dół</button>' .
			'</div>' .
			'</div>' .
			"\n";
		
		while($translation = $sense->get_translation()){
			$this->parse_translation($translation);
		}
		
		$this->output .=
			'<div><button class="button add_translation" onclick="add_translation(this.parentNode.parentNode, ' .
			$sense->get_id() .
			')">dodaj wpis</button></div>' . "\n";
		
		
		while($subsense = $sense->get_sense()){
			$this->parse_sense($subsense);
		}
		
		$this->output .= '</div>' . "\n";
	}
	
	private function parse_translation(Translation $translation){
		$this->output .=
			'<div class="translation">' .
			$translation->get_text() .
			'<div class="buttons">' .
			'<button class="button move_up" onclick="move_translation_up(this.parentNode.parentNode, ' .
			$translation->get_id() .
			')">do góry</button>' .
			'<button class="button move_down" onclick="move_translation_down(this.parentNode.parentNode, ' .
			$translation->get_id() .
			')">na dół</buton>' .
			'</div>' .
			'</div>' .
			"\n";
	}
}

?>

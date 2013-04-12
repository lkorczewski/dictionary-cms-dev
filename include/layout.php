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

	function parse(Entry $entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}

	private function parse_entry(Entry $entry){
		$this->output .= '<div class="entry_container">' . "\n";
		$this->output .= '<div class="headword">' . $entry->get_headword() . '</div>' . "\n";
		
		// senses
		while($sense = $entry->get_sense()){
			$this->parse_sense($sense);
		}
		
		// closing entry container
		$this->output .= '</div>' . "\n";
	}
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="sense_label_bar">' . "\n" .
				'<div class="sense_label">' . $sense->get_label() . '</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					'<button class="button move_up" onclick="move_sense_up(this.parentNode.parentNode.parentNode, ' .
					$sense->get_id() .
					')"> do góry</button>' . "\n" .
					'<button class="button move_down" onclick="move_sense_down(this.parentNode.parentNode.parentNode, ' .
					$sense->get_id() .
					')"> na dół</button>' . "\n" .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $sense->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
		// new translation
		$this->output .=
			'<div><button class="button add_translation" onclick="add_translation(this.parentNode.parentNode, ' .
			$sense->get_id() .
			')">dodaj wpis</button></div>' . "\n";
		
		// subsenses
		while($subsense = $sense->get_sense()){
			$this->parse_sense($subsense);
		}
		
		// closing sense container
		$this->output .= '</div>' . "\n";
	}
	
	private function parse_translation(Translation $translation){
		$this->output .=
			'<div class="translation_bar">' . "\n" .
				'<div class="translation">' . $translation->get_text() . '</div>' . "\n" .
				'<div class="buttons">' . "\n" .
				
					'<button class="button delete" onclick="delete_translation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">usuń</button>' . "\n" .
					
					'<button class="button move_up" onclick="move_translation_up(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">do góry</button>' . "\n" .
					
					'<button class="button move_down" onclick="move_translation_down(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">na dół</buton>' . "\n" .
					
					'<button class="button edit" onclick="edit_translation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">zmień</buton>' . "\n" .
				
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}
}

?>

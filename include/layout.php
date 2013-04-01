<?php

require_once __DIR__.'/dictionary.php';
require_once __DIR__.'/entry.php';
require_once __DIR__.'/sense.php';
require_once __DIR__.'/translation.php';

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
		$this->output .= '<div class="entry_container">'."\n";
		$this->output .= '<p class="headword"><b>'.$entry->get_headword().'</b></p>'."\n";
		
		while($sense = $entry->get_sense()){
			$this->parse_sense($sense);
		}
		$this->output .= '<div>'."\n";
	}
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">';
		$this->output .= '<p class="sense_label">'.$sense->get_label().'</p>'."\n";
		
		while($translation = $sense->get_translation()){
			$this->parse_translation($translation);
		}
		
		while($subsense = $sense->get_sense()){
			$this->parse_sense($subsense);
		}
		
		$this->output .= '</div>'."\n";
	}
	
	private function parse_translation(Translation $translation){
		$this->output .= '<p class="translation">'.$translation->get().'</p>'."\n";
	}
}

?>

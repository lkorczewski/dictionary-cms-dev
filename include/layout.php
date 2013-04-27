<?php

require_once 'dictionary/dictionary.php';

require_once 'dictionary/entry.php';
require_once 'dictionary/sense.php';
require_once 'dictionary/phrase.php';

require_once 'dictionary/form.php';
require_once 'dictionary/translation.php';

class Layout{
	
	private $output;
	private $depth;
	
	//--------------------------------------------------------------------
	// constructor
	//--------------------------------------------------------------------
	
	function __construct(){
		$this->output = '';
		$this->depth = 0;
	}
	
	//--------------------------------------------------------------------
	// public parser
	//--------------------------------------------------------------------

	function parse(Entry $entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}
	
	//--------------------------------------------------------------------
	// private entry parser
	//--------------------------------------------------------------------

	private function parse_entry(Entry $entry){
		$this->output .= '<div class="entry_container">' . "\n";
		$this->output .= '<div class="headword">' . $entry->get_headword() . '</div>' . "\n";
		$this->output .= '<div class="entry_content">' . "\n";
		
		// forms
		while($form = $entry->get_form()){
			$this->parse_form($form);
		}
		
		// senses
		while($sense = $entry->get_sense()){
			$this->parse_sense($sense);
		}
		
		// new sense
		$this->output .=
			'<div><button class="button add_sense" onclick="add_sense(this.parentNode.parentNode, ' .
			$entry->get_node_id() .
			')">dodaj znaczenie</button></div>' . "\n";
		
		// closing entry content
		$this->output .= '</div>' . "\n";
		
		// closing entry container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// private sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="sense_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="sense_label">' . $sense->get_label() . '</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button delete" onclick="delete_sense(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">usuń</button>' . "\n" .
					
					'<button class="button move_up" onclick="move_sense_up(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">do góry</button>' . "\n" .
					
					'<button class="button move_down" onclick="move_sense_down(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">na dół</button>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="sense_content">' . "\n";
		
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $sense->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
		// new translation
		$this->output .=
			'<div><button class="button addTranslation" onclick="addTranslation(this.parentNode.parentNode, ' .
			$sense->get_node_id() .
			')">dodaj wpis</button></div>' . "\n";
		
		// phrases
		$this->output .= '<div class="phrases">' . "\n";
		while($phrase = $sense->get_phrase()){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
		// new phrase
		$this->output .=
			'<div><button class="button add_phrase" onclick="addPhrase(this.parentNode.parentNode, ' .
			$sense->get_node_id() .
			')">dodaj wyrażenie</button></div>' . "\n";
		
		// subsenses
		while($subsense = $sense->get_sense()){
			$this->parse_sense($subsense);
		}

		// closing sense content
		$this->output .= '</div>' . "\n";
		
		// closing sense container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(Form $form){
		$this->output .=
			'<div class="form_bar">' . "\n" .
				'<div class="form_label">' . $form->get_label() . '</div>' . "\n" .
				'<div class="form">' . $form->get_form() . '</div>' . "\n" .
			'</div>' . "\n"
			;
	}

	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="phrase_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="phrase" onclick="editPhrase(this.parentNode, ' .
				$phrase->get_node_id() .
				')">' .
				$phrase->get() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button delete" onclick="deletePhrase(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">usuń</button>' . "\n" .
					
					'<button class="button move_up" onclick="movePhraseUp(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">do góry</button>' . "\n" .
					
					'<button class="button move_down" onclick="movePhraseDown(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">na dół</button>' . "\n" .
					
					'<button class="button edit" onclick="editPhrase(this.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">zmień</buton>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="phrase_content">' . "\n";
		
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $phrase->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
		// new translation
		$this->output .=
			'<div><button class="button addTranslation" onclick="addTranslation(this.parentNode.parentNode, ' .
			$phrase->get_node_id() .
			')">dodaj tłumaczenie</button></div>' . "\n";
		
		// closing phrase content
		$this->output .= '</div>' . "\n";
		
		// closing phrase container
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(Translation $translation){
		$this->output .=
			'<div class="translation_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="translation" onclick="editTranslation(this.parentNode, ' .
				$translation->get_id() .
				')">' .
				$translation->get_text()
				. '</div>' . "\n" .
				'<div class="buttons">' . "\n" .
				
					'<button class="button delete" onclick="deleteTranslation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">usuń</button>' . "\n" .
					
					'<button class="button move_up" onclick="moveTranslationUp(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">do góry</button>' . "\n" .
					
					'<button class="button move_down" onclick="moveTranslationDown(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">na dół</buton>' . "\n" .
					
					'<button class="button edit" onclick="editTranslation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">zmień</buton>' . "\n" .
				
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}
}

?>

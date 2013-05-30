<?php

require_once 'dictionary/dictionary.php';

require_once 'dictionary/entry.php';
require_once 'dictionary/sense.php';
require_once 'dictionary/phrase.php';

require_once 'dictionary/form.php';
require_once 'dictionary/translation.php';

require_once 'include/localization.php';

class View_Layout{
	
	private $output;
	private $depth;
	private $localization;
	
	//--------------------------------------------------------------------
	// constructor
	//--------------------------------------------------------------------
	
	function __construct($localization){
		$this->output = '';
		$this->depth = 0;
		$this->localization = $localization;
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
		
		// category label
		$this->parse_category_label($entry);
		
		// forms
		$this->parse_forms($entry);
		
		// senses
		$this->parse_senses($entry);
		
		// closing entry content
		$this->output .= '</div>' . "\n";
		
		// closing entry container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// private sense nest parser
	//--------------------------------------------------------------------
	
	private function parse_senses(/*Node*/ $node){
		
		// senses
		while($sense = $node->get_sense()){
			$this->parse_sense($sense);
		}
		
	}
	
	//--------------------------------------------------------------------
	// private sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="sense_label_bar">' . "\n" .
				'<div class="sense_label">' .
				$sense->get_label() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="sense_content">' . "\n";
		
		// category label
		$this->parse_category_label($sense);
		
		// forms
		$this->parse_forms($sense);
		
		// translations
		$this->parse_translations($sense);
		
		// phrases
		$this->parse_phrases($sense);
		
		// subsenses
		$this->parse_senses($sense);

		// closing sense content
		$this->output .= '</div>' . "\n";
		
		// closing sense container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// phrase nest parser
	//--------------------------------------------------------------------
	
	private function parse_phrases(/*Node*/ $node){
		
		// phrases
		$this->output .= '<div class="phrases">' . "\n";
		while($phrase = $node->get_phrase()){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="phrase_bar">' . "\n" .
				'<div class="phrase">' .
				$phrase->get() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="phrase_content">' . "\n";
		
		// translations
		$this->parse_translations($phrase);
		
		// closing phrase content
		$this->output .= '</div>' . "\n";
		
		// closing phrase container
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(Headword_Node $node){
		
		if($category_label = $node->get_category_label()){
			$this->output .=
				'<div class="category_label_bar">' . "\n" .
					'<div class="category_label">' .
					$category_label->get() .
					'</div>' . "\n" .
				'</div>' . "\n";
		}
		
	}
	
	//--------------------------------------------------------------------
	// form nest parser
	//--------------------------------------------------------------------
	
	private function parse_forms(Headword_Node $node){
		
		// forms
		$this->output .= '<div class="forms">' . "\n";
		while($form = $node->get_form()){
			$this->parse_form($form);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(Form $form){
		$this->output .=
			'<div class="form_bar">' . "\n" .
				'<div class="form_label">' .
				$form->get_label() .
				'</div>' . "\n" .
				'<div class="form">' .
				$form->get_form() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}

	//--------------------------------------------------------------------
	// translation nest parser
	//--------------------------------------------------------------------
	
	private function parse_translations(Node $node){
	
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $node->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(Translation $translation){
		$this->output .=
			'<div class="translation_bar">' . "\n" .
				'<div class="translation">' .
				$translation->get_text()
				. '</div>' . "\n" .
			'</div>' . "\n"
			;
	}
}

?>
